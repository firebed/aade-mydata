<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use DOMElement;
use Firebed\AadeMyData\Exceptions\ErrorResponseException;
use Firebed\AadeMyData\Models\Model;

/**
 * @template T of Model
 */
abstract class XMLReader
{
    private DOMDocument $document;

    /**
     * Whether this reader reads a ResponseDoc. Every other reader takes a ResponseDoc
     * as myDATA reporting errors instead of the expected document, and throws them.
     */
    protected bool $readsResponseDoc = false;

    /**
     * @throws ErrorResponseException
     */
    protected function loadXML(string $xmlString, Model $parent): void
    {
        $document = new DOMDocument;
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;
        $document->loadXML($xmlString);

        $this->loadDocument($document, $parent);
    }

    /**
     * @param  DOMDocument  $document  Loaded without whitespace nodes (preserveWhiteSpace = false)
     *
     * @throws ErrorResponseException
     */
    protected function loadDocument(DOMDocument $document, Model $parent): void
    {
        $this->document = $document;

        if (! $this->readsResponseDoc && ($error = ErrorResponseException::fromDocument($document)) !== null) {
            throw $error;
        }

        // myDATA may answer with values this package does not know (e.g. a newly
        // added invoice type). Those are read as null instead of failing the whole document.
        if ($document->documentElement !== null) {
            Model::lenient(fn () => $this->parseDOMElement($document->documentElement, $parent));
        }
    }

    /**
     * Fills the model with the child elements of the given element.
     */
    protected function parseDOMElement(DOMElement $element, Model $model): void
    {
        for ($child = $element->firstElementChild; $child !== null; $child = $child->nextElementSibling) {
            $this->parseSimpleElement($child, $model);
        }
    }

    /**
     * Reads one element into the parent, as the parent's cast for it says: a model or
     * collection cast gets a model built from the children (an empty wrapper such as
     * <receptionEmails/> gives an empty collection), anything else gets the text.
     * Elements the parent declares nothing for are ignored, so XML myDATA extends
     * with elements this package does not know still parses.
     */
    protected function parseSimpleElement(DOMElement $element, Model $parent): void
    {
        $name = $element->localName;
        $cast = $parent->cast($name);

        if ($element->childElementCount === 0 && ! $cast?->isCollection()) {
            $parent->set($name, $element->nodeValue);

            return;
        }

        $model = $cast?->newModel();

        if ($model === null) {
            return;
        }

        $this->parseDOMElement($element, $model);

        // A list attribute (Arr::of) collects repeated elements, a single one is replaced
        $parent->set($name, $model);
    }

    public function getDomDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @return T
     */
    abstract public function parseXml(string $xmlString): mixed;
}

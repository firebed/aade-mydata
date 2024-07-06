<?php

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use DOMElement;
use Firebed\AadeMyData\Models\Type;
use IteratorAggregate;

/**
 * @template T of Type
 */
abstract class XMLReader
{
    private DOMDocument $document;

    protected function loadXML(string $xmlString, Type $parent): void
    {
        $this->document = new DOMDocument();
        $this->document->preserveWhiteSpace = false;
        $this->document->formatOutput = true;
        $this->document->loadXML($xmlString);

        $this->parseDOMElement($this->document->documentElement->childNodes, $parent);
    }

    /**
     * Parse the XML node and return the corresponding Type object.
     */
    protected function parseDOMElement(IteratorAggregate $elements, Type $parent = null): void
    {
        // Since we have no control over the XML returned by myDATA,
        // it is possible to encounter node names within the XML that
        // are not handled by this project. In the event of an unhandled
        // unknown node type, rather than throwing an exception, it is 
        // preferred to ignore the unhandled node entirely and allow the
        // application to proceed with the remaining elements.
        if ($parent === null) {
            return;
        }

        // Parse the child nodes
        foreach ($elements as $child) {
            $this->parseSimpleElement($child, $parent);
        }
    }

    protected function parseSimpleElement(DOMElement $element, Type $parent): void
    {
        $name = $element->localName;

        if (!$element->childElementCount) {
            $parent->set($name, $element->nodeValue);
            return;
        }

        $type = $this->createType($parent, $name);
        $this->parseDOMElement($element->childNodes, $type);
        if ($parent instanceof IteratorAggregate) {
            $parent->push($name, $type);
        } else {
            $parent->set($name, $type);
        }
    }

    protected function createType(Type $parent, string $name): mixed
    {
        $cast = $parent->getCast($name);
        return $cast ? new $cast() : null;
    }

    public function getDomDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @param  string  $xmlString
     * @return T
     */
    public abstract function parseXml(string $xmlString): mixed;
}
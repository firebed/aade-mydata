<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use BackedEnum;
use DOMDocument;
use DOMElement;
use DOMException;
use DOMNode;
use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * @template T
 */
abstract class XMLWriter
{
    // DOMDocument object for XML manipulation
    protected DOMDocument $document;

    /**
     * Namespaces of the elements, as 'element' => ['' => namespace of the element, 'child' => …, '*' => every child].
     *
     * @var array<string, array<string, string>>
     */
    protected array $namespaces = [];

    public function __construct()
    {
        $this->document = new DOMDocument('1.0', 'utf-8');
        $this->document->preserveWhiteSpace = false;
    }

    /**
     * @throws DOMException
     */
    protected function build(DOMNode $parent, string $nodeName, mixed $nodeValue, ?string $namespace = null): void
    {
        if (is_null($nodeValue) || (is_string($nodeValue) && strlen($nodeValue) === 0)) {
            return;
        }

        // An empty wrapper element (e.g. <paymentMethods/>) fails the XSD, so it is left out
        if ($nodeValue instanceof ModelCollection && $nodeValue->isEmpty()) {
            return;
        }

        if ($nodeValue instanceof Model) {
            $this->buildModel($parent, $nodeName, $nodeValue);

            return;
        }

        if (is_array($nodeValue)) {
            $this->buildArray($parent, $nodeName, $nodeValue);

            return;
        }

        $namespaceURI = $this->getNamespace($namespace);
        $child = $this->createElement($nodeName, $this->toValue($nodeValue), $namespaceURI);
        $parent->appendChild($child);
    }

    /**
     * @throws DOMException
     */
    protected function buildModel(DOMNode $parent, string $nodeName, Model $nodeValue): DOMNode
    {
        return $this->processAssocArray($parent, $nodeName, $nodeValue->sortedAttributes());
    }

    /**
     * @param  array<int|string, mixed>  $nodeValue
     *
     * @throws DOMException
     */
    protected function buildArray(DOMNode $parent, string $nodeName, array $nodeValue): void
    {
        // If the array is associative
        if (! array_is_list($nodeValue)) {
            $this->processAssocArray($parent, $nodeName, $nodeValue);

            return;
        }

        foreach ($nodeValue as $value) {
            $this->build($parent, $nodeName, $value);
        }
    }

    /**
     * @param  array<int|string, mixed>  $nodeValue
     *
     * @throws DOMException
     */
    protected function processAssocArray(DOMNode $parent, string $nodeName, array $nodeValue): DOMNode
    {
        $namespaceURI = $this->getNamespace($nodeName);

        $child = $this->createElement($nodeName, namespaceURI: $namespaceURI);
        $parent->appendChild($child);

        foreach ($nodeValue as $key => $value) {
            $this->build($child, (string) $key, $value, $nodeName.'.'.$key);
        }

        return $child;
    }

    /**
     * @throws DOMException
     */
    protected function createElement(string $nodeName, ?string $nodeValue = null, ?string $namespaceURI = null): DOMElement
    {
        // An element without a namespace of its own is in the document's default namespace,
        // as it reads once serialized, so the DOM validates against the XSD as it is
        $namespaceURI ??= $this->document->documentElement?->namespaceURI;

        // Create the element (with or without namespace)
        $newNode = $namespaceURI
            ? $this->document->createElementNS($namespaceURI, $nodeName)
            : $this->document->createElement($nodeName);

        // If a node value is provided, handle special characters
        if (! is_null($nodeValue)) {
            $newNode->appendChild($this->document->createTextNode($nodeValue));
        }

        return $newNode;
    }

    protected function toValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value instanceof BackedEnum) {
            return (string) $value->value;
        }

        return (string) $value;
    }

    protected function getNamespace(?string $key): ?string
    {
        if (is_null($key)) {
            return null;
        }

        // If key contains '.', split it into parent and child
        if (str_contains($key, '.')) {
            [$parent, $child] = explode('.', $key, 2);

            // If child exists, return corresponding namespace URI
            if ($child) {
                return $this->namespaces[$parent][$child] ?? $this->namespaces[$parent]['*'] ?? null;
            }
        }

        return $this->namespaces[$key][''] ?? null;
    }

    public function getDomDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * Writes the data and validates the document against a myDATA XSD by name (e.g. 'InvoicesDoc').
     *
     * @param  T  $data
     * @return list<array{field: string|null, message: string|null}> The validation errors, empty when valid
     */
    public function validate(mixed $data, string $schema): array
    {
        $this->asXml($data);

        return Schema::validate($this->document, $schema);
    }

    /**
     * @param  T  $data
     */
    abstract public function asXml(mixed $data): string;
}

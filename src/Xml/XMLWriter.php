<?php

namespace Firebed\AadeMyData\Xml;

use BackedEnum;
use DOMDocument;
use DOMElement;
use DOMException;
use DOMNode;
use Firebed\AadeMyData\Models\Type;

/**
 * @template T
 */
abstract class XMLWriter
{
    // DOMDocument object for XML manipulation
    protected DOMDocument $document;

    public function __construct()
    {
        $this->document = new DOMDocument('1.0', 'utf-8');
        $this->document->preserveWhiteSpace = false;
        $this->document->formatOutput = true;
    }

    /**
     * @throws DOMException
     */
    protected function build(DOMNode $parent, string $nodeName, mixed $nodeValue, $namespace = null): void
    {
        if (is_null($nodeValue) || (is_string($nodeValue) && strlen($nodeValue) === 0)) {
            return;
        }
        
        if ($nodeValue instanceof Type) {
            $this->buildType($parent, $nodeName, $nodeValue);
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
    protected function buildType(DOMNode $parent, string $nodeName, Type $nodeValue): DOMNode
    {
        return $this->processAssocArray($parent, $nodeName, $nodeValue->sortedAttributes());
    }

    /**
     * @throws DOMException
     */
    protected function buildArray(DOMNode $parent, string $nodeName, array $nodeValue): void
    {
        // If the array is associative
        if (is_string(array_key_first($nodeValue))) {
            $this->processAssocArray($parent, $nodeName, $nodeValue);
            return;
        }

        foreach ($nodeValue as $value) {
            $this->build($parent, $nodeName, $value);
        }
    }

    /**
     * @throws DOMException
     */
    protected function processAssocArray(DOMNode $parent, string $nodeName, array $nodeValue): DOMNode
    {
        $namespaceURI = $this->getNamespace($nodeName);

        $child = $this->createElement($nodeName, namespaceURI: $namespaceURI);
        $parent->appendChild($child);

        foreach ($nodeValue as $key => $value) {
            $this->build($child, $key, $value, $nodeName.'.'.$key);
        }

        return $child;
    }


    /**
     * @throws DOMException
     */
    protected function createElement(string $nodeName, mixed $nodeValue = null, string $namespaceURI = null): DOMElement
    {
        // Create the element (with or without namespace)
        $newNode = $namespaceURI
            ? $this->document->createElementNS($namespaceURI, $nodeName)
            : $this->document->createElement($nodeName);

        // If a node value is provided, handle special characters
        if (!is_null($nodeValue)) {
            $newNode->appendChild($this->document->createTextNode($nodeValue));
        }
        
        return $newNode;
    }

    protected function toValue($value): ?string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } 
        
        if ($value instanceof BackedEnum) {
            return $value->value;
        }
        
        return (string)$value;
    }

    protected function getNamespace(?string $key): ?string
    {
        if (is_null($key)) {
            return null;
        }

        // If key contains '.', split it into parent and child
        if (str_contains($key, '.')) {
            [$parent, $child] = explode('.', $key);

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
     * @param T $data
     * @return string
     */
    public abstract function asXml($data): string;
}
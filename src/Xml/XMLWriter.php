<?php

namespace Firebed\AadeMyData\Xml;

use BackedEnum;
use DOMDocument;
use DOMElement;
use DOMNode;
use Firebed\AadeMyData\Models\Type;

class XMLWriter
{
    // Array to store namespaces
    protected array $namespaces = [];

    // DOMDocument object for XML manipulation
    protected DOMDocument $document;

    public function __construct()
    {
        $this->document = new DOMDocument('1.0', 'utf-8');
        $this->document->preserveWhiteSpace = false;
        $this->document->formatOutput = true;
    }

    protected function build(DOMNode $parent, string $nodeName, mixed $nodeValue, $namespace = null): void
    {
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

    protected function buildType(DOMNode $parent, string $nodeName, Type $nodeValue): DOMNode
    {
        return $this->processAssocArray($parent, $nodeName, $nodeValue->sortedAttributes());
    }

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


    /** @noinspection PhpUnhandledExceptionInspection */
    protected function createElement(string $nodeName, mixed $nodeValue = null, string $namespaceURI = null): DOMElement
    {
        if (is_null($nodeValue)) {
            if ($namespaceURI) {
                return $this->document->createElementNS($namespaceURI, $nodeName);
            }

            return $this->document->createElement($nodeName);
        }

        if ($namespaceURI) {
            return $this->document->createElementNS($namespaceURI, $nodeName, $nodeValue);
        }

        return $this->document->createElement($nodeName, $nodeValue);
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
}
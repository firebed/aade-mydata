<?php

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use DOMElement;
use DOMNode;
use Firebed\AadeMyData\Models\Type;

class XMLWriter
{
    protected array $namespaces = [];

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
        $namespaceURI = $this->getNamespace($nodeName);
        $child = $this->createElement($nodeName, namespaceURI: $namespaceURI);
        $parent->appendChild($child);
        foreach ($nodeValue->sortedAttributes() as $key => $value) {
            $this->build($child, $key, $value, $nodeName.'.'.$key);
        }

        return $child;
    }

    protected function buildArray(DOMNode $parent, string $nodeName, array $nodeValue): void
    {
        if (is_int(array_key_first($nodeValue))) {
            foreach ($nodeValue as $value) {
                $this->build($parent, $nodeName, $value);
            }

            return;
        }

        $namespaceURI = $this->getNamespace($nodeName);
        $child = $this->createElement($nodeName, namespaceURI: $namespaceURI);
        $parent->appendChild($child);
        foreach ($nodeValue as $key => $value) {
            $this->build($child, $key, $value, $nodeName.'.'.$key);
        }
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
        return is_bool($value) ? ($value ? 'true' : 'false') : (string)$value;
    }

    protected function getNamespace(?string $key): ?string
    {
        if (is_null($key)) {
            return null;
        }

        if (str_contains($key, '.')) {
            [$parent, $child] = explode('.', $key);

            if ($child) {
                return $this->namespaces[$parent][$child] ?? $this->namespaces[$parent]['*'] ?? null;
            }
        }

        return $this->namespaces[$key][''] ?? null;
    }
}
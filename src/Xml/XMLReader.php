<?php

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use DOMElement;
use Firebed\AadeMyData\Models\Type;

class XMLReader
{
    private array $class_map;

    public function __construct(array $class_map)
    {
        $this->class_map = $class_map;
    }

    protected function loadXML(string $xmlString): mixed
    {
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($xmlString);
        
        return $this->parseDOMElement($doc->documentElement);
    }

    /**
     * Parse the XML node and return the corresponding Type object.
     */
    protected function parseDOMElement(DOMElement $element): mixed
    {
        $relatedType = $this->createType($element->localName);

        // Since we have no control over the XML returned by myDATA,
        // it is possible to encounter node names within the XML that
        // are not handled by this project. In the event of an unhandled
        // unknown node type, rather than throwing an exception, it is 
        // preferred to ignore the unhandled node entirely and allow the
        // application to proceed with the remaining elements.
        if ($relatedType === null) {
            return null;
        }

        // Parse the child nodes
        foreach ($element->childNodes as $child) {
            if ($this->isGroupElement($child, $relatedType)) {
                // Certain nodes function as arrays, serving as containers for
                // similar child types. For instance, an invoice may have a
                // 'paymentMethods' property, acting as a wrapper array to store
                // instances of PaymentMethodDetail type.
                $this->parseWrapperElement($child, $relatedType);
            } else {
                // Some nodes may represent objects that extend the Type class,
                // while others may simply be values such as strings, integers,
                // booleans, etc.
                $this->parseSimpleElement($child, $relatedType);
            }
        }

        return $relatedType;
    }

    protected function parseWrapperElement(DOMElement $element, Type $parent): void
    {
        // Wrapper nodes should function like arrays, so we just need to
        // iterate though it and parse its children.
        $values = [];
        foreach ($element->childNodes as $child) {
            $values[] = $this->parseDOMElement($child);
        }

        $parent->set($element->localName, $values);
    }

    protected function parseSimpleElement(DOMElement $element, Type $parent): void
    {
        $name = $element->localName;
        $value = $element->childElementCount ? $this->parseDOMElement($element) : $element->nodeValue;
        $parent->set($name, $value);
    }

    protected function createType(string $name): mixed
    {
        if (!array_key_exists($name, $this->class_map)) {
            return null;
        }

        return new $this->class_map[$name]();
    }

    protected function isGroupElement(DOMElement $element, Type $type): bool
    {
        return isset($type->groups) && in_array($element->localName, $type->groups);
    }
}
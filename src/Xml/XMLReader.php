<?php

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use DOMElement;
use Firebed\AadeMyData\Models\Type;
use IteratorAggregate;

class XMLReader
{
    protected function loadXML(string $xmlString, Type $parent): void
    {
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($xmlString);

        $this->parseDOMElement($doc->documentElement->childNodes, $parent);
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

        $res = $this->createType($parent, $name);

        if (is_array($res)) {
            $this->parseDOMElement($element->childNodes, $parent);
            return;
        }

        if ($element->childElementCount) {
            $this->parseDOMElement($element->childNodes, $res);
            if ($parent instanceof IteratorAggregate) {
                $parent->push($name, $res);
            } else {
                $parent->set($name, $res);
            }
        } else {
            $parent->set($name, $element->nodeValue);
        }
    }

    protected function createType(Type $parent, string $name): mixed
    {
        if (isset($parent->casts[$name])) {
            $type = $parent->casts[$name];
            if (str_contains($type, ':')) {
                return explode(':', $type);
            }
            return new $type();
        }

        return null;
    }
}
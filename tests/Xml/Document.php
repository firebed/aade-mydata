<?php

namespace Tests\Xml;

use DOMDocument;
use DOMNode;

class Document
{
    private DOMDocument $dom;
    private Node        $xml;

    public function __construct(string $xmlString)
    {
        $this->dom = new DOMDocument();
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = true;
        $this->dom->loadXML($xmlString);

        $this->xml = new Node($this->toArray($this->dom));
    }

    private function toArray(DOMNode $root): array|string|null
    {
        $result = [];
        $groups = [];

        // Process child nodes
        foreach ($root->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                return $child->nodeValue;
            }

            $nodeName = $child->nodeName;

            // Check if the node already exists in the result array
            if (!isset($result[$nodeName])) {
                $result[$nodeName] = $this->toArray($child);
            } else {
                // Convert to array if it's not already an array
                if (!isset($groups[$nodeName])) {
                    $result[$nodeName] = [$result[$child->nodeName]];
                    $groups[$nodeName] = true;
                }

                // Append to the existing array
                $result[$nodeName][] = $this->toArray($child);
            }
        }

        return $result;
    }

    public function __get($name): object
    {
        return $this->xml->$name;
    }
}
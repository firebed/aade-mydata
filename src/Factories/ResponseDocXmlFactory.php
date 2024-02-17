<?php

namespace Firebed\AadeMyData\Factories;

use DOMDocument;
use Firebed\AadeMyData\Models\Response;

class ResponseDocXmlFactory
{
    private array $responses = [];

    public function addResponse(Response|Factory $response): void
    {
        if ($response instanceof Factory) {
            $response = $response->make();
        }
        
        $response->set('index', count($this->responses) + 1);
        $this->responses[] = $response;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXML(): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $responseDoc = $dom->createElement('ResponseDoc');
        $responseDoc->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $responseDoc->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $dom->appendChild($responseDoc);

        foreach ($this->responses as $response) {
            $responseNode = $dom->createElement('response');
            $responseDoc->appendChild($responseNode);

            foreach ($response->attributes() as $key => $value) {
                $node = $dom->createElement($key, $value);
                $responseNode->appendChild($node);
            }
        }

        return $dom->saveXML();
    }
}
<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Factories;

use DOMDocument;
use DOMElement;
use Firebed\AadeMyData\Models\Response;
use Firebed\AadeMyData\Models\Statements\StatementResponse;
use Firebed\AadeMyData\Xml\Dom;

/**
 * Builds the XML of a ResponseDoc as myDATA answers it, for tests and examples.
 */
class ResponseDocXmlFactory
{
    /** @var list<Response|StatementResponse> */
    private array $responses = [];

    /**
     * @param  Response|StatementResponse|Factory<Response>|Factory<StatementResponse>  $response  A response, or a factory of one or more
     */
    public function addResponse(Response|StatementResponse|Factory $response): void
    {
        if ($response instanceof Factory) {
            $made = $response->make();

            foreach (is_array($made) ? $made : [$made] as $response) {
                $this->addResponse($response);
            }

            return;
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

            foreach ($response->toArray() as $key => $value) {
                $this->append($dom, $responseNode, $key, $value);
            }
        }

        return Dom::save($dom);
    }

    /**
     * Appends an element per value: a list repeats the element, an associative array nests its entries.
     */
    private function append(DOMDocument $dom, DOMElement $parent, string $key, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (! is_array($value)) {
            $parent->appendChild($dom->createElement($key, is_bool($value) ? ($value ? 'true' : 'false') : (string) $value));

            return;
        }

        if (array_is_list($value)) {
            foreach ($value as $item) {
                $this->append($dom, $parent, $key, $item);
            }

            return;
        }

        $node = $dom->createElement($key);
        $parent->appendChild($node);

        foreach ($value as $childKey => $childValue) {
            $this->append($dom, $node, (string) $childKey, $childValue);
        }
    }
}

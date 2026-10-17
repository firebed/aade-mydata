<?php

namespace Firebed\AadeMyData\Http\Traits;

use DOMDocument;
use Firebed\AadeMyData\Exceptions\ErrorResponseException;
use Firebed\AadeMyData\Xml\XMLReader;

trait HasResponseDom
{
    private ?DOMDocument $responseDom = null;

    public function getResponseDom(): ?DOMDocument
    {
        return $this->responseDom;
    }

    public function getResponseXML(bool $formatOutput = true): ?string
    {
        if ($this->responseDom === null) {
            return null;
        }

        $this->responseDom->formatOutput = $formatOutput;
        return $this->responseDom->saveXML();
    }

    public function getResponseElement(string $localName, int $index): ?string
    {
        if ($this->responseDom === null) {
            return null;
        }

        $element = $this->responseDom->getElementsByTagName($localName)->item($index);
        return $this->responseDom->saveXML($element);
    }

    /**
     * Parse the response with the given reader and keep its DOM for getResponseXML(),
     * also when the reader finds myDATA's errors in it and throws ErrorResponseException.
     *
     * @template T
     * @param XMLReader<T> $reader
     * @return T
     * @throws ErrorResponseException
     */
    protected function read(string $responseXml, XMLReader $reader): mixed
    {
        try {
            return $reader->parseXml($responseXml);
        } finally {
            $this->responseDom = $reader->getDomDocument();
        }
    }
}

<?php

namespace Firebed\AadeMyData\Http\Traits;

use DOMDocument;

trait HasResponseDom
{
    private ?DOMDocument $responseDom = null;

    public function getResponseDom(): ?DOMDocument
    {
        return $this->responseDom;
    }

    public function getResponseXML(bool $formatOutput = true): ?string
    {
        $this->responseDom->formatOutput = $formatOutput;
        return $this->responseDom?->saveXML();
    }

    public function getResponseElement(string $localName, int $index): ?string
    {
        if ($this->responseDom === null) {
            return null;
        }
        
        $element = $this->responseDom->getElementsByTagName($localName)->item($index);
        return $this->responseDom->saveXML($element);
    }
}
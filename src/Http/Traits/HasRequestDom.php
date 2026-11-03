<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http\Traits;

use DOMDocument;
use Firebed\AadeMyData\Xml\Dom;

trait HasRequestDom
{
    private ?DOMDocument $requestDom = null;

    public function getRequestDom(): ?DOMDocument
    {
        return $this->requestDom;
    }

    public function getRequestXml(bool $formatOutput = true): ?string
    {
        if ($this->requestDom === null) {
            return null;
        }

        $this->requestDom->formatOutput = $formatOutput;

        return Dom::save($this->requestDom);
    }

    public function getRequestElement(string $localName, int $index): ?string
    {
        if ($this->requestDom === null) {
            return null;
        }

        $element = $this->requestDom->getElementsByTagName($localName)->item($index);

        return Dom::save($this->requestDom, $element);
    }
}

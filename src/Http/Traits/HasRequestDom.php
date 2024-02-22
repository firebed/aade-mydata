<?php

namespace Firebed\AadeMyData\Http\Traits;

use DOMDocument;

trait HasRequestDom
{
    private ?DOMDocument $requestDom = null;

    public function getRequestDom(): ?DOMDocument
    {
        return $this->requestDom;
    }
}
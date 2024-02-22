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
}
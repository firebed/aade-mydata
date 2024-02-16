<?php

namespace Firebed\AadeMyData\Http\Traits;

trait HasResponseXML
{
    private ?string $responseXML = null;

    public function getResponseXML(): ?string
    {
        return $this->responseXML;
    }
}
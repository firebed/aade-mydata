<?php

namespace Firebed\AadeMyData\Http\Traits;

trait HasRequestXML
{
    private ?string $requestXML = null;

    public function getRequestXML(): ?string
    {
        return $this->requestXML;
    }
}
<?php

namespace Firebed\AadeMyData\Models;

class ResponseDoc extends Type
{
    /**
     * @return ResponseType[]
     */
    public function getResponseTypes(): array
    {
        return $this->properties();
    }
    
    public function addResponse(ResponseType $response): self
    {
        return $this->put('', $response);
    }

    public function put($key, $value): self
    {
        $this->attributes[] = $value;
        return $this;
    }
}
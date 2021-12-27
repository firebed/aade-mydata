<?php

namespace Firebed\AadeMyData\Models;

class ResponseDoc extends Type
{
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
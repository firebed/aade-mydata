<?php

namespace Firebed\AadeMyData\Models;

class Errors extends Type
{
    public function addError(ErrorType $error): self
    {
        return $this->put('', $error);
    }
    
    public function put($key, $value): self
    {
        $this->attributes[] = $value;
        return $this;
    }
}
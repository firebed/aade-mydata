<?php

namespace Firebed\AadeMyData\Models;

class Errors extends Type
{
    /**
     * @return ErrorType[]
     */
    public function getErrorsTypes(): array
    {
        return $this->properties();
    }
    
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
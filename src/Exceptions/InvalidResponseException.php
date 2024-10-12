<?php

namespace Firebed\AadeMyData\Exceptions;

class InvalidResponseException extends MyDataException
{
    public function __construct($message = "Invalid response received from AADE MyData API", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
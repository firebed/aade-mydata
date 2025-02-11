<?php

namespace Firebed\AadeMyData\Exceptions;

class MyDataTimeoutException extends MyDataException
{
    public function __construct($message = "myDATA request timed out", int $code = 28, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
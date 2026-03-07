<?php

namespace Firebed\AadeMyData\Exceptions;

use Throwable;

class InvalidResponseException extends MyDataException
{
    public function __construct(string $message = "Invalid response received from AADE MyData API", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
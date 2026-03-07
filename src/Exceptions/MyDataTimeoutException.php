<?php

namespace Firebed\AadeMyData\Exceptions;

use Throwable;

class MyDataTimeoutException extends MyDataException
{
    public function __construct(string $message = "myDATA request timed out", int $code = 28, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
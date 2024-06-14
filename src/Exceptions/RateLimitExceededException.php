<?php

namespace Firebed\AadeMyData\Exceptions;

use Throwable;

class RateLimitExceededException extends TransmissionFailedException
{
    public function __construct(string $message = null, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
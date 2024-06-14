<?php

namespace Firebed\AadeMyData\Exceptions;


use Throwable;

class MyDataAuthenticationException extends TransmissionFailedException
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Authentication failed. Please check your user id and subscription key.", $code, $previous);
    }
}
<?php

namespace Firebed\AadeMyData\Exceptions;


use Throwable;

class MyDataAuthenticationException extends TransmissionFailedException
{
    /**
     * @param string|null $reason The reason given by myDATA, if any (e.g. the user is not authorized for the requested resource)
     */
    public function __construct(int $code = 0, ?Throwable $previous = null, ?string $reason = null)
    {
        parent::__construct($reason ?: "Authentication failed. Please check your user id and subscription key.", $code, $previous);
    }
}
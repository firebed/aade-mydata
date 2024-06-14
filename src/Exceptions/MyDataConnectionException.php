<?php

namespace Firebed\AadeMyData\Exceptions;

use Throwable;

class MyDataConnectionException extends TransmissionFailedException
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct("myDATA servers are down or unreachable. Please try again later.", $code, $previous);
    }
}
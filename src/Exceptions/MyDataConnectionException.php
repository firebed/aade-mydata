<?php

namespace Firebed\AadeMyData\Exceptions;

class MyDataConnectionException extends MyDataException
{
    public function __construct()
    {
        parent::__construct("myDATA servers are down or unreachable. Please try again later.");
    }
}
<?php

namespace Firebed\AadeMyData\Exceptions;

class MyDataAuthenticationException extends MyDataException
{
    public function __construct()
    {
        parent::__construct("Authentication failed. Please check your user id and subscription key.", 401);
    }
}
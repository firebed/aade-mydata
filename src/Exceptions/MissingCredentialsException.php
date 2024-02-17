<?php

namespace Firebed\AadeMyData\Exceptions;

class MissingCredentialsException extends MyDataException
{
    public function __construct()
    {
        parent::__construct("Missing credentials. Please use MyDataRequest::setCredentials method to set your myDATA Rest API credentials.");
    }
}
<?php

namespace Firebed\AadeMyData\Models;

/**
 * This class is used to store reception email and is part of
 * <code>Response</code> class.
 *
 * @extends TypeArray<string>
 */
class ReceptionEmails extends TypeArray
{
    public function __construct()
    {
        parent::__construct('email');
    }

    public function set($key, $value): static
    {
        return $this->push('email', $value);
    }
}
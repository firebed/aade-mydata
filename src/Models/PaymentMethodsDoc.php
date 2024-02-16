<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class PaymentMethodsDoc extends Type implements IteratorAggregate, Countable, ArrayAccess
{
    use HasIterator;
}
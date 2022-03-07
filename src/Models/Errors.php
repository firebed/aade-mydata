<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class Errors extends Type implements IteratorAggregate, Countable
{
    use HasIterator;
}
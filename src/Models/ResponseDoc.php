<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class ResponseDoc extends Type implements IteratorAggregate, Countable
{
    use HasIterator;
}
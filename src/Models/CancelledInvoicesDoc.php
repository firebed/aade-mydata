<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class CancelledInvoicesDoc extends Type implements IteratorAggregate, Countable
{
    use HasIterator;
}
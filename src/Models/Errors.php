<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * @extends ModelCollection<Error>
 */
class Errors extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'error' => Arr::of(Error::class),
        ];
    }
}

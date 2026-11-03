<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * This class is used to store reception email and is part of
 * <code>Response</code> class.
 *
 * @extends ModelCollection<string>
 */
class ReceptionEmails extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'email' => Arr::of('string'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * @extends ModelCollection<RequestedStatement>
 *
 * @version 1.0.12
 */
class RequestedStatementDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'requestedStatement' => Arr::of(RequestedStatement::class),
        ];
    }
}

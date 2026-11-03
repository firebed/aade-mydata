<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * @extends ModelCollection<StatementResponse>
 *
 * @version 1.0.12
 */
class StatementResponseDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'response' => Arr::of(StatementResponse::class),
        ];
    }
}

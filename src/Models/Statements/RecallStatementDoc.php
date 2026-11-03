<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * @extends ModelCollection<RecalledStatementType>
 *
 * @version 1.0.12
 */
class RecallStatementDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'recalledStatement' => Arr::of(RecalledStatementType::class),
        ];
    }
}

<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * @extends TypeArray<RecalledStatementType>
 *
 * @version 1.0.12
 */
class RecallStatementDoc extends TypeArray
{
    protected array $casts = [
        'recalledStatement' => RecalledStatementType::class,
    ];

    public function __construct()
    {
        parent::__construct('recalledStatement');
    }
}

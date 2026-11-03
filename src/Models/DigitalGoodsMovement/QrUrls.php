<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * @extends ModelCollection<string>
 *
 * @version 2.0.1
 */
class QrUrls extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'qrUrl' => Arr::of('string'),
        ];
    }
}

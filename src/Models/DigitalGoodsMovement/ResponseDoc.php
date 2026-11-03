<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * @extends ModelCollection<Response>
 *
 * @version 2.0.1
 */
class ResponseDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'response' => Arr::of(Response::class),
        ];
    }
}

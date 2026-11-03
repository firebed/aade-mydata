<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * This class is used to store the delivery lifecycle of a digital good movement.
 *
 * @extends ModelCollection<DeliveryEvent>
 *
 * @version 2.0.1
 */
class DeliveryLifecycle extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'deliveryEvents' => Arr::of(DeliveryEvent::class),
        ];
    }
}

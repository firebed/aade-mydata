<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * This class is used to store the delivery lifecycle of a digital good movement.
 *
 * @extends TypeArray<DeliveryEvent>
 * @version 2.0.1
 */
class DeliveryLifecycle extends TypeArray
{
    protected array $casts = [
        'deliveryEvents' => DeliveryEvent::class,
    ];

    /**
     * @param DeliveryEvent|DeliveryEvent[] $events
     */
    public function __construct(array $events = [])
    {
        parent::__construct('deliveryEvents', $events);
    }
}
<?php

namespace Tests;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use PHPUnit\Framework\TestCase;

class DeliveryStatusTest extends TestCase
{
    public function test_in_transit_return_is_status_9(): void
    {
        $this->assertSame(DeliveryStatus::IN_TRANSIT_RETURN, DeliveryStatus::from(9));
        $this->assertSame('Σε διακίνηση (Επιστροφή)', DeliveryStatus::IN_TRANSIT_RETURN->label());
        $this->assertSame(DeliveryStatus::IN_TRANSIT_RETURN, DeliveryStatus::tryFromName('IN_TRANSIT_RETURN'));
    }

    public function test_names_are_read_in_both_the_dev_and_the_specification_spelling(): void
    {
        $spellings = [
            'Registered' => DeliveryStatus::REGISTERED,
            'Cancelled' => DeliveryStatus::CANCELLED,
            'InTransit' => DeliveryStatus::IN_TRANSIT,
            'Rejected' => DeliveryStatus::REJECTED,
            'DeliveredByCarrier' => DeliveryStatus::DELIVERED_BY_CARRIER,
            'FailedDelivery' => DeliveryStatus::FAILED_DELIVERY,
            'Completed' => DeliveryStatus::COMPLETED,
            'InTransit (Return)' => DeliveryStatus::IN_TRANSIT_RETURN,
        ];

        foreach ($spellings as $name => $status) {
            $this->assertSame($status, DeliveryStatus::tryFromName($name), $name);
            $this->assertSame($status, DeliveryStatus::tryFromName($status->name), $status->name);
        }

        $this->assertNull(DeliveryStatus::tryFromName('Unknown'));
        $this->assertNull(DeliveryStatus::tryFromName(''));
    }
}

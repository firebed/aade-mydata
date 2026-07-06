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
}

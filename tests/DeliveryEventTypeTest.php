<?php

namespace Tests;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryEventType;
use PHPUnit\Framework\TestCase;

class DeliveryEventTypeTest extends TestCase
{
    public function test_confirm_return_event(): void
    {
        $this->assertSame(DeliveryEventType::CONFIRM_RETURN, DeliveryEventType::from('ConfirmReturn'));
        $this->assertSame('Επιβεβαίωση επιστροφής', DeliveryEventType::CONFIRM_RETURN->label());
    }

    public function test_register_transfer_return_event(): void
    {
        $this->assertSame(DeliveryEventType::REGISTER_TRANSFER_RETURN, DeliveryEventType::from('RegisterTransferReturn'));
        $this->assertSame('Επιστροφή διακίνησης', DeliveryEventType::REGISTER_TRANSFER_RETURN->label());
    }
}

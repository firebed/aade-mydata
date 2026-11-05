<?php

declare(strict_types=1);

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

/**
 * Sandbox, 2026-11-05: myDATA rejects false on these booleans (error 214, "must be sent only
 * if it is true"), except vatPaymentSuspension, which it stores as false when left out.
 * False stays on the model but never reaches the XML.
 */
class TrueOnlyAttributesTest extends TestCase
{
    use HandlesInvoiceXml;

    private const array HEADER_FLAGS = [
        'vatPaymentSuspension',
        'selfPricing',
        'fuelInvoice',
        'isDeliveryNote',
        'thirdPartyCollection',
        'totalCancelDeliveryOrders',
        'reverseDeliveryNote',
        'nonObligatedRecipient',
        'withoutDigitalTransportTracking',
    ];

    private const array ROW_FLAGS = ['discountOption', 'notVAT195'];

    public static function headerFlags(): array
    {
        return array_map(fn ($flag) => [$flag], self::HEADER_FLAGS);
    }

    public static function rowFlags(): array
    {
        return array_map(fn ($flag) => [$flag], self::ROW_FLAGS);
    }

    public function test_the_models_list_exactly_the_sandbox_checked_flags(): void
    {
        $this->assertSame(self::HEADER_FLAGS, (new InvoiceHeader)->getTrueOnly());
        $this->assertSame(self::ROW_FLAGS, (new InvoiceDetails)->getTrueOnly());
    }

    #[DataProvider('headerFlags')]
    public function test_a_header_flag_is_written_only_when_true(string $flag): void
    {
        $invoice = Invoice::factory()->make();
        $header = $invoice->getInvoiceHeader()->set($flag, false);

        $this->assertFalse($header->get($flag));
        $this->assertFalse($header->toArray()[$flag]);
        $this->assertNull($this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader->get($flag));

        $header->set($flag, true);
        $this->assertSame('true', $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader->get($flag));
    }

    #[DataProvider('rowFlags')]
    public function test_a_row_flag_is_written_only_when_true(string $flag): void
    {
        $row = InvoiceDetails::factory()->make()->set($flag, false);
        $invoice = Invoice::factory()->make()->setInvoiceDetails([$row]);

        $this->assertFalse($row->get($flag));
        $this->assertNull($this->toXML($invoice)->InvoicesDoc->invoice->invoiceDetails->get($flag));

        $row->set($flag, true);
        $this->assertSame('true', $this->toXML($invoice)->InvoicesDoc->invoice->invoiceDetails->get($flag));
    }

    public function test_to_weigh_false_is_sent(): void
    {
        // myDATA accepts toWeigh=false and stores it, while a left-out toWeigh is stored as nothing
        $invoice = Invoice::factory()->make();
        $invoice->getInvoiceHeader()->setToWeigh(false);

        $this->assertSame('false', $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader->get('toWeigh'));
    }
}

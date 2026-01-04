<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\TransportDetail;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceTransportDetailsTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_other_transport_details_is_not_present_in_the_xml(): void
    {
        $invoice = Invoice::factory()->make();
        $invoice->setOtherTransportDetails([
            new TransportDetail('KHB6611'),
        ]);

        $transportDetailsXml = $this->toXML($invoice)->InvoicesDoc->invoice->get('otherTransportDetails');

        $this->assertNotNull($invoice->getOtherTransportDetails());
        $this->assertNull($transportDetailsXml);
    }
}
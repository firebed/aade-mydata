<?php

use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_to_xml()
    {
        
    }

    public function test_it_converts_xml_to_invoice()
    {
        // uid
        // mark
        // cancelledByMark
        // authenticationCode
        // transmissionFailure
        // qrCodeUrl
    }
}
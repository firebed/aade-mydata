<?php

use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_populates_invoice_auto_filled_attributes()
    {
        $doc = $this->getRequestedDocFromXml();
        
        $this->assertCount(2, $doc->getInvoices());
        
        $invoice = $doc->getInvoices()->offsetGet(1);

        $this->assertEquals('5AD65A46SFD5498SDV416WS5F1VS65VDFS65VDF', $invoice->getUid());
        $this->assertEquals('800000165789544', $invoice->getMark());
        $this->assertEquals('800000165989544', $invoice->getCancelledByMark());
        $this->assertEquals('54AS56DS65VF4S65DF', $invoice->getAuthenticationCode());
        $this->assertEquals(3, $invoice->getTransmissionFailure());
        $this->assertEquals('https://www.akjjasd.com/asdkasdkasdkas?asasd=asdasd', $invoice->getQrCodeUrl());
    }
}
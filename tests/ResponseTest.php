<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class ResponseTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_reads_response_correctly()
    {
        $doc = $this->getResponseDocFromXml('response-doc-with-reception-emails');

        $this->assertCount(1, $doc);
        $this->assertEquals(1, $doc[0]->getIndex());
        $this->assertEquals("6F825B9B74717280D1F9A38252D0B65604C6D162", $doc[0]->getInvoiceUid());
        $this->assertEquals("480301204040191", $doc[0]->getInvoiceMark());
        $this->assertEquals("https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=sSsxThUx5i3O8ankrp8XUDiL0q12vbzuWZP0nEfMgAyQ2nbm7s6vSz3CYYcmod6i0uCrPCvSLrQ5N1qxEHF8JsfMNz6NmibNhBNXJiR8ecC%3d", $doc[0]->getQrUrl());
        $this->assertEquals("Success", $doc[0]->getStatusCode());
        
        $this->assertIsArray($doc[0]->getReceptionEmails()->all());
        $this->assertCount(2, $doc[0]->getReceptionEmails());
        $this->assertEquals("accounting@test.gr", $doc[0]->getReceptionEmails()->offsetGet(0));
        $this->assertEquals("invoices@test.gr", $doc[0]->getReceptionEmails()->offsetGet(1));
    }
    
    public function test_it_reads_errors_correctly()
    {
        $doc = $this->getResponseDocFromXml('response-doc-with-errors');
        
        $this->assertCount(2, $doc);
        
        $errors1 = $doc[0]->getErrors();
        $this->assertCount(2, $errors1);
        $this->assertEquals("At least one payment method detail must be of POS type", $errors1[0]->getMessage());
        $this->assertEquals(402, $errors1[0]->getCode());
        $this->assertEquals("Payment Methods total amount must be equal to or less than initial invoice's total value", $errors1[1]->getMessage());
        $this->assertEquals(407, $errors1[1]->getCode());

        $errors2 = $doc[1]->getErrors();
        $this->assertCount(2, $errors2);
        $this->assertEquals("At least one payment method detail must be of POS type", $errors2[0]->getMessage());
        $this->assertEquals(402, $errors2[0]->getCode());
        $this->assertEquals("Payment Methods total amount must be equal to or less than initial invoice's total value", $errors2[1]->getMessage());
        $this->assertEquals(407, $errors2[1]->getCode());
    }
}
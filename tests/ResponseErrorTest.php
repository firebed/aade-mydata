<?php

use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class ResponseErrorTest extends TestCase
{
    use HandlesInvoiceXml;

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
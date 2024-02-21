<?php

namespace Tests;

use Firebed\AadeMyData\Enums\PaymentMethod;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class PaymentMethodsDocTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_xml_to_payment_methods()
    {
        $doc = $this->getRequestedDocFromXml();

        $paymentMethods = $doc->getPaymentMethods();

        $this->assertCount(3, $paymentMethods);

        $paymentMethodDetail = $paymentMethods[0]->getPaymentMethodDetails()[0];
        $this->assertEquals(PaymentMethod::METHOD_7, $paymentMethodDetail->getType());
        $this->assertEquals(10, $paymentMethodDetail->getAmount());
        $this->assertEquals('PLM111', $paymentMethodDetail->getTransactionId());
        $this->assertEquals('TESLA', $paymentMethodDetail->getECRToken()->getSigningAuthor());
        $this->assertEquals('AAA111', $paymentMethodDetail->getECRToken()->getSessionNumber());
        $this->assertEquals(1111, $paymentMethods[0]->getInvoiceMark());
        $this->assertEquals(2222, $paymentMethods[0]->getPaymentMethodMark());
        $this->assertEquals(888888888, $paymentMethods[0]->getEntityVatNumber());

        $paymentMethodDetail = $paymentMethods[1]->getPaymentMethodDetails()[0];
        $this->assertEquals(PaymentMethod::METHOD_3, $paymentMethodDetail->getType());
        $this->assertEquals(10, $paymentMethodDetail->getAmount());
        $this->assertEquals('SSA569', $paymentMethodDetail->getTransactionId());
        $this->assertEquals('ORBIT', $paymentMethodDetail->getECRToken()->getSigningAuthor());
        $this->assertEquals('AAA222', $paymentMethodDetail->getECRToken()->getSessionNumber());
        $this->assertEquals(3333, $paymentMethods[1]->getInvoiceMark());
        $this->assertEquals(4444, $paymentMethods[1]->getPaymentMethodMark());
        $this->assertEquals(888888888, $paymentMethods[1]->getEntityVatNumber());

        $paymentMethodDetail = $paymentMethods[2]->getPaymentMethodDetails()[0];
        $this->assertEquals(PaymentMethod::METHOD_4, $paymentMethodDetail->getType());
        $this->assertEquals(20, $paymentMethodDetail->getAmount());
        $this->assertEquals('ERA544', $paymentMethodDetail->getTransactionId());
        $this->assertEquals('ACME', $paymentMethodDetail->getECRToken()->getSigningAuthor());
        $this->assertEquals('AAA333', $paymentMethodDetail->getECRToken()->getSessionNumber());
        $this->assertEquals(5555, $paymentMethods[2]->getInvoiceMark());
        $this->assertEquals(5555, $paymentMethods[2]->getPaymentMethodMark());
        $this->assertEquals(999999999, $paymentMethods[2]->getEntityVatNumber());
    }
}

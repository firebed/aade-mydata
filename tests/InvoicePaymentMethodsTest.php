<?php

namespace Tests;

use Firebed\AadeMyData\Enums\PaymentMethod;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\PaymentMethods;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoicePaymentMethodsTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_single_invoice_payment_method_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $paymentMethods = $invoice->getPaymentMethods();

        $paymentMethodsXml = $this->toXML($invoice)->InvoicesDoc->invoice->paymentMethods->paymentMethodDetails;

        $this->assertCount(1, $paymentMethods); // Refers to payment method detail attributes

        // Test payment method 1
        $payment = $paymentMethods[0];
        $paymentXml = $paymentMethodsXml;
        $this->assertEquals($payment->getType()->value, $paymentXml->type);
        $this->assertEquals($payment->getAmount(), $paymentXml->amount);
        $this->assertEquals($payment->getPaymentMethodInfo(), $paymentXml->paymentMethodInfo);
        $this->assertEquals($payment->getTipAmount(), $paymentXml->tipAmount);
        $this->assertEquals($payment->getTransactionId(), $paymentXml->transactionId);
    }

    public function test_it_converts_multiple_invoice_payment_methods_to_xml(): void
    {
        $invoice = Invoice::factory()
            ->state([
                'paymentMethods' => PaymentMethods::factory()->state([
                    'paymentMethodDetails' => PaymentMethodDetail::factory(2)
                ])
            ])
            ->make();

        $paymentMethods = $invoice->getPaymentMethods();
        $paymentMethodsXml = $this->toXML($invoice)->InvoicesDoc->invoice->paymentMethods->paymentMethodDetails;

        $this->assertCount(2, $paymentMethodsXml);

        // Test payment method 1
        $payment1 = $paymentMethods[0];
        $payment1Xml = $paymentMethodsXml[0];
        $this->assertEquals($payment1->getType()->value, $payment1Xml->type);
        $this->assertEquals($payment1->getAmount(), $payment1Xml->amount);
        $this->assertEquals($payment1->getPaymentMethodInfo(), $payment1Xml->paymentMethodInfo);
        $this->assertEquals($payment1->getTipAmount(), $payment1Xml->tipAmount);
        $this->assertEquals($payment1->getTransactionId(), $payment1Xml->transactionId);

        // Test payment method 2
        $payment2 = $paymentMethods[1];
        $payment2Xml = $paymentMethodsXml[1];
        $this->assertEquals($payment2->getType()->value, $payment2Xml->type);
        $this->assertEquals($payment2->getAmount(), $payment2Xml->amount);
        $this->assertEquals($payment2->getPaymentMethodInfo(), $payment2Xml->paymentMethodInfo);
        $this->assertEquals($payment2->getTipAmount(), $payment2Xml->tipAmount);
        $this->assertEquals($payment2->getTransactionId(), $payment2Xml->transactionId);
    }

    public function test_it_converts_multiple_invoice_payment_methods_to_xml_using_wrapper(): void
    {
        $invoice = Invoice::factory()->make();
        $invoice->setPaymentMethods(new PaymentMethods([
            PaymentMethodDetail::factory(),
            PaymentMethodDetail::factory()
        ]));

        $this->assertCount(2, $invoice->getPaymentMethods());
    }
    
    public function test_it_converts_multiple_invoice_payment_methods_to_xml_using_array(): void
    {
        $invoice = Invoice::factory()->make();
        $invoice->setPaymentMethods([PaymentMethodDetail::factory(), PaymentMethodDetail::factory()]);
        
        $this->assertCount(2, $invoice->getPaymentMethods());
    }
    
    public function test_it_converts_multiple_invoice_payment_methods_to_xml_using_add(): void
    {
        $invoice = Invoice::factory()->except(['paymentMethods'])->make();
        $invoice->addPaymentMethod(PaymentMethodDetail::factory()->make());
        $invoice->addPaymentMethod(PaymentMethodDetail::factory()->make());

        $this->assertCount(2, $invoice->getPaymentMethods());
    }
    
    public function test_it_converts_xml_to_invoice_payment_methods(): void
    {
        $invoice = $this->getInvoiceFromXml();

        $paymentMethods = $invoice->getPaymentMethods();
        
        $this->assertCount(2, $paymentMethods);

        $this->assertEquals(PaymentMethod::METHOD_1, $paymentMethods[0]->getType());
        $this->assertEquals(6000, $paymentMethods[0]->getAmount());
        $this->assertEquals('GR1234556668877922', $paymentMethods[0]->getPaymentMethodInfo());

        $this->assertEquals(PaymentMethod::METHOD_7, $paymentMethods[1]->getType());
        $this->assertEquals(205, $paymentMethods[1]->getAmount());
        $this->assertEquals('Τοις Μετρητοίς', $paymentMethods[1]->getPaymentMethodInfo());
        $this->assertEquals(5, $paymentMethods[1]->getTipAmount());
        $this->assertEquals('66554488', $paymentMethods[1]->getTransactionId());
        $this->assertEquals('AA1155698', $paymentMethods[1]->getECRToken()->getSigningAuthor());
        $this->assertEquals('123456', $paymentMethods[1]->getECRToken()->getSessionNumber());
    }
}
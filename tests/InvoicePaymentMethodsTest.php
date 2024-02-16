<?php

namespace Tests;

use Firebed\AadeMyData\Enums\PaymentMethod;
use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoicePaymentMethodsTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_payment_methods_to_xml(): void
    {
        $paymentMethod1 = $this->createPaymentMethodDetail(PaymentMethod::METHOD_1, 10, 'GR123456789', 4, '1166A');
        $paymentMethod1->setECRToken('AA55', '123456');

        $paymentMethod2 = $this->createPaymentMethodDetail(PaymentMethod::METHOD_3, 15, 'Τοις Μετρητοίς');
        $paymentMethod2->setProvidersSignature("PROV", "AACCBB55");

        $invoice = new Invoice();
        $invoice->addPaymentMethod($paymentMethod1);
        $invoice->addPaymentMethod($paymentMethod2);

        $xml = $this->toXML($invoice)->InvoicesDoc->invoice;

        $paymentMethods = $xml->paymentMethods->paymentMethodDetails;

        // Payment method assertions
        $this->assertCount(2, $paymentMethods);

        // First payment method
        $firstPaymentMethod = $paymentMethods[0];
        $this->assertEquals(PaymentMethod::METHOD_1->value, $firstPaymentMethod->type);
        $this->assertEquals(10, $firstPaymentMethod->amount);
        $this->assertEquals('GR123456789', $firstPaymentMethod->paymentMethodInfo);
        $this->assertEquals(4, $firstPaymentMethod->tipAmount);
        $this->assertEquals('1166A', $firstPaymentMethod->transactionId);

        // Second payment method
        $secondPaymentMethod = $paymentMethods[1];
        $this->assertEquals(PaymentMethod::METHOD_3->value, $secondPaymentMethod->type);
        $this->assertEquals(15, $secondPaymentMethod->amount);
        $this->assertEquals('Τοις Μετρητοίς', $secondPaymentMethod->paymentMethodInfo);
        $this->assertEmpty($secondPaymentMethod->tipAmount);
        $this->assertEmpty($secondPaymentMethod->transactionId);
    }

    public function test_invoice_payment_methods_are_parsed(): void
    {
        $invoice = $this->getInvoiceFromXml();

        $paymentMethods = $invoice->getPaymentMethods();

        $this->assertCount(2, $paymentMethods);

        $this->assertEquals(PaymentMethod::METHOD_1->value, $paymentMethods[0]->getType());
        $this->assertEquals(6000, $paymentMethods[0]->getAmount());
        $this->assertEquals('GR1234556668877922', $paymentMethods[0]->getPaymentMethodInfo());

        $this->assertEquals(PaymentMethod::METHOD_7->value, $paymentMethods[1]->getType());
        $this->assertEquals(205, $paymentMethods[1]->getAmount());
        $this->assertEquals('Τοις Μετρητοίς', $paymentMethods[1]->getPaymentMethodInfo());
        $this->assertEquals(5, $paymentMethods[1]->getTipAmount());
        $this->assertEquals('66554488', $paymentMethods[1]->getTransactionId());
        $this->assertEquals('AA1155698', $paymentMethods[1]->getECRToken()->getSigningAuthor());
        $this->assertEquals('123456', $paymentMethods[1]->getECRToken()->getSessionNumber());
    }
}
<?php

declare(strict_types=1);

namespace Tests;

use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use PHPUnit\Framework\TestCase;

class PaymentMethodXmlTest extends TestCase
{
    private function paymentMethod(): PaymentMethod
    {
        return (new PaymentMethod)
            ->setInvoiceMark(400001)
            ->addPaymentMethodDetails((new PaymentMethodDetail)->setType(PaymentType::CASH)->setAmount(12.4));
    }

    public function test_to_xml_returns_the_payment_methods_element_only(): void
    {
        $xml = $this->paymentMethod()->toXml();

        $this->assertStringStartsWith('<paymentMethods', $xml);
        $this->assertStringContainsString('<invoiceMark>400001</invoiceMark>', $xml);
        $this->assertStringNotContainsString('PaymentMethodsDoc', $xml);
    }

    public function test_to_xml_as_doc_returns_the_whole_document(): void
    {
        $xml = $this->paymentMethod()->toXml(true);

        $this->assertStringStartsWith('<?xml', $xml);
        $this->assertStringContainsString('<PaymentMethodsDoc', $xml);
    }

    public function test_validate_uses_the_payment_methods_schema(): void
    {
        $this->assertSame([], $this->paymentMethod()->validate());

        $errors = (new PaymentMethod)->setInvoiceMark(400001)->validate();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('field', $errors[0]);
    }
}

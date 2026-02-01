<?php

namespace Tests;

use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\ProvidersSignature;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class ProviderSignatureTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_xml_to_payment_methods()
    {
        $providerSignature = new ProvidersSignature();
        $providerSignature->setEndToEndReferenceID('REF12345');
        $providerSignature->setSigningAuthor('01234567');
        $providerSignature->setSignature('SIGNATUREDATA');

        $this->assertEquals('REF12345', $providerSignature->getEndToEndReferenceID());
        $this->assertEquals('01234567', $providerSignature->getSigningAuthor());
        $this->assertEquals('SIGNATUREDATA', $providerSignature->getSignature());
    }

    public function test_end_to_end_reference_id_is_included_in_the_xml()
    {
        $providerSignature = new ProvidersSignature();
        $providerSignature->setEndToEndReferenceID('REF12345');
        $providerSignature->setSigningAuthor('01234567');
        $providerSignature->setSignature('SIGNATUREDATA');

        $payment = new PaymentMethod();
        $payment->setInvoiceMark(12345);
        $payment->addPaymentMethodDetails(PaymentMethodDetail::factory()->make([
            'ProvidersSignature' => $providerSignature,
        ]));

        $xml = $payment->toXml();

        $this->assertEmpty($payment->validate());
        $this->assertStringContainsString('<inv:SigningAuthor>01234567</inv:SigningAuthor>', $xml);
        $this->assertStringContainsString('<inv:Signature>SIGNATUREDATA</inv:Signature>', $xml);
        $this->assertStringContainsString('<inv:EndToEndReferenceID>REF12345</inv:EndToEndReferenceID>', $xml);
    }
}

<?php

namespace Tests;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\ReceivingNotePurpose;
use Firebed\AadeMyData\Enums\ReverseDeliveryNotePurpose;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_issue_time_stays_on_the_model_but_is_never_sent_to_mydata(): void
    {
        $invoice = Invoice::factory()->make();
        $header = $invoice->getInvoiceHeader()->setIssueTime('10:15:00');

        $this->assertSame('10:15:00', $header->getIssueTime());
        $this->assertSame('10:15:00', $header->toArray()['issueTime']);
        $this->assertSame('10:15:00', InvoiceHeader::make(['issueDate' => '2026-08-27', 'issueTime' => '10:15:00'])->getIssueTime());
        $this->assertArrayNotHasKey('issueTime', $header->sortedAttributes());
        $this->assertNull($this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader->issueTime);
    }

    public function test_it_converts_invoice_header_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $header = $invoice->getInvoiceHeader();
        $headerXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader;

        $this->assertCount(28, $headerXml);
        $this->assertEquals($header->getSeries(), $headerXml->series);
        $this->assertEquals($header->getAa(), $headerXml->aa);
        $this->assertEquals($header->getIssueDate(), $headerXml->issueDate);
        $this->assertEquals($header->getInvoiceType()->value, $headerXml->invoiceType);
        $this->assertEquals($header->isVatPaymentSuspension(), filter_var($headerXml->vatPaymentSuspension, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getCurrency(), $headerXml->currency);
        $this->assertEquals($header->getExchangeRate(), $headerXml->exchangeRate);
        $this->assertEquals($header->isSelfPricing(), filter_var($headerXml->selfPricing, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getDispatchDate(), $headerXml->dispatchDate);
        $this->assertEquals($header->getDispatchTime(), $headerXml->dispatchTime);
        $this->assertEquals($header->getVehicleNumber(), $headerXml->vehicleNumber);
        $this->assertEquals($header->getMovePurpose()->value, $headerXml->movePurpose);
        $this->assertEquals($header->isFuelInvoice(), filter_var($headerXml->fuelInvoice, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getSpecialInvoiceCategory()->value, $headerXml->specialInvoiceCategory);
        $this->assertEquals($header->getInvoiceVariationType()->value, $headerXml->invoiceVariationType);
        $this->assertEquals($header->getIsDeliveryNote(), filter_var($headerXml->isDeliveryNote, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getOtherMovePurposeTitle(), $headerXml->otherMovePurposeTitle);
        $this->assertEquals($header->getThirdPartyCollection(), filter_var($headerXml->thirdPartyCollection, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getTableAA(), $headerXml->tableAA);
        $this->assertEquals($header->getTotalCancelDeliveryOrders(), filter_var($headerXml->totalCancelDeliveryOrders, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getReverseDeliveryNote(), filter_var($headerXml->reverseDeliveryNote, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getReverseDeliveryNotePurpose()->value, $headerXml->reverseDeliveryNotePurpose);
        $this->assertEquals($header->getToWeigh(), filter_var($headerXml->toWeigh, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($header->getReceivingNotePurpose()->value, $headerXml->receivingNotePurpose);
    }

    public function test_it_converts_xml_to_invoice_header(): void
    {
        $header = $this->getInvoiceFromXml()->getInvoiceHeader();

        $this->assertCount(24, $header->attributes());

        $this->assertEquals('A', $header->getSeries());
        $this->assertEquals(101, $header->getAa());
        $this->assertEquals('2020-04-08', $header->getIssueDate());
        $this->assertEquals(InvoiceType::TYPE_1_1, $header->getInvoiceType());
        $this->assertFalse($header->isVatPaymentSuspension());
        $this->assertEquals('EUR', $header->getCurrency());

        $this->assertTrue($header->isSelfPricing());
        $this->assertEquals('2024-02-13', $header->getDispatchDate());
        $this->assertEquals('00:00', $header->getDispatchTime());
        $this->assertEquals('KHB4201', $header->getVehicleNumber());
        $this->assertEquals(MovePurpose::TYPE_19, $header->getMovePurpose());
        $this->assertTrue($header->isFuelInvoice());
        $this->assertEquals(SpecialInvoiceCategory::TYPE_5, $header->getSpecialInvoiceCategory());
        $this->assertEquals(InvoiceVariationType::TYPE_3, $header->getInvoiceVariationType());
        $this->assertTrue($header->getThirdPartyCollection());
    }

    public function test_it_sets_and_casts_v2_0_2_receiving_note_fields(): void
    {
        $header = new InvoiceHeader();
        $header->setReceivingNotePurpose(7)
            ->setOtherReceivingNotePurposeTitle('Λοιπή περίπτωση')
            ->setNonObligatedRecipient(true)
            ->setWithoutDigitalTransportTracking(false);

        $this->assertSame(ReceivingNotePurpose::OTHER_CASES, $header->getReceivingNotePurpose());
        $this->assertSame('Λοιπή περίπτωση', $header->getOtherReceivingNotePurposeTitle());
        $this->assertTrue($header->isNonObligatedRecipient());
        $this->assertFalse($header->isWithoutDigitalTransportTracking());
    }

    public function test_it_converts_v2_0_2_receiving_note_fields_to_xml(): void
    {
        $invoice = Invoice::factory()->make();
        $invoice->getInvoiceHeader()
            ->setReceivingNotePurpose(ReceivingNotePurpose::OTHER_CASES)
            ->setOtherReceivingNotePurposeTitle('Λοιπή')
            ->setNonObligatedRecipient(true);

        $headerXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader;

        $this->assertEquals('7', (string) $headerXml->receivingNotePurpose);
        $this->assertEquals('Λοιπή', (string) $headerXml->otherReceivingNotePurposeTitle);
        $this->assertTrue(filter_var($headerXml->nonObligatedRecipient, FILTER_VALIDATE_BOOLEAN));
    }

    public function test_reverse_delivery_note_purpose_casts_int_to_enum(): void
    {
        $header = new InvoiceHeader();
        $header->setReverseDeliveryNotePurpose(3);

        $this->assertSame(
            ReverseDeliveryNotePurpose::INTRA_COMMUNITY_ACQUISITION,
            $header->getReverseDeliveryNotePurpose()
        );
    }
}
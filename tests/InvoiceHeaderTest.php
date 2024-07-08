<?php

namespace Tests;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;
use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_header_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $header = $invoice->getInvoiceHeader();
        $headerXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader;

        $this->assertCount(24, $headerXml);
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
}
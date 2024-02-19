<?php

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\TransportDetail;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceTransportDetailsTest extends TestCase
{
    use HandlesInvoiceXml;
    
    public function test_it_converts_single_invoice_transport_details_to_xml(): void
    {
        $invoice = Invoice::factory()->make();
        $transportDetails = $invoice->getOtherTransportDetails();
        
        $transportDetailsXml = $this->toXML($invoice)->InvoicesDoc->invoice->otherTransportDetails;
        
        $this->assertCount(1, $transportDetails);
        
        $this->assertEquals($transportDetails[0]->getVehicleNumber(), $transportDetailsXml->vehicleNumber);
    }

    public function test_it_converts_multiple_invoice_transport_details_to_xml(): void
    {
        $invoice = Invoice::factory()
            ->state(['otherTransportDetails' => TransportDetail::factory(2)])
            ->make();

        $transportDetails = $invoice->getOtherTransportDetails();
        $transportDetailsXml = $this->toXML($invoice)->InvoicesDoc->invoice->otherTransportDetails;
        
        $this->assertCount(2, $transportDetails);
        $this->assertCount(2, $transportDetailsXml);
        
        $this->assertEquals($transportDetails[0]->getVehicleNumber(), $transportDetailsXml[0]->vehicleNumber);
        $this->assertEquals($transportDetails[01]->getVehicleNumber(), $transportDetailsXml[1]->vehicleNumber);
    }
    
    public function test_it_converts_xml_to_invoice_transport_details()
    {
        $invoice = $this->getInvoiceFromXml();

        $transportDetails = $invoice->getOtherTransportDetails();

        $this->assertCount(2, $transportDetails);
        $this->assertEquals('KHB6611', $transportDetails[0]->getVehicleNumber());
        $this->assertEquals('AHB9090', $transportDetails[1]->getVehicleNumber());
    }
}
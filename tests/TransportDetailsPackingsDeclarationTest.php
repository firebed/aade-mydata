<?php

namespace Tests;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\TransportDetails;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class TransportDetailsPackingsDeclarationTest extends TestCase
{
    public function test_it_serializes_repeated_packings_declaration_to_xml(): void
    {
        $details = new TransportDetails();
        $details->setVehicleNumber('ABC1234');
        $details->setTransportType(1);
        $details->setCarrierVatNumber('123456789');
        $details->setPackingsDeclaration([
            new PackagingDetail(PackagingType::PALLET, 3),
            new PackagingDetail(PackagingType::BOX, 5),
        ]);

        $transport = new Transport();
        $transport->setQrUrl('https://example.test/qr');
        $transport->setTransportDetail($details);

        $xml = new SimpleXMLElement($transport->toXml());
        $packings = $xml->transportDetail->packingsDeclaration;

        $this->assertCount(2, $packings);
        $this->assertEquals('1', (string) $packings[0]->packagingType);
        $this->assertEquals('3', (string) $packings[0]->quantity);
        $this->assertEquals('2', (string) $packings[1]->packagingType);
        $this->assertEquals('5', (string) $packings[1]->quantity);
    }

    public function test_transport_with_packings_declaration_passes_xsd_validation(): void
    {
        $details = new TransportDetails();
        $details->setVehicleNumber('ABC1234');
        $details->setTransportType(1);
        $details->setCarrierVatNumber('123456789');
        $details->addPackingsDeclaration(new PackagingDetail(PackagingType::PALLET, 3));

        $transport = new Transport();
        $transport->setQrUrl('https://example.test/qr');
        $transport->setTransportDetail($details);

        $this->assertEmpty($transport->validate());
    }

    public function test_repeated_elements_are_collected_when_read(): void
    {
        // Mirrors how XMLReader populates a non-collection Type: set() is called once per element.
        $details = new TransportDetails();
        $details->set('packingsDeclaration', new PackagingDetail(PackagingType::PALLET, 1));
        $details->set('packingsDeclaration', new PackagingDetail(PackagingType::BOX, 2));

        $this->assertCount(2, $details->getPackingsDeclaration());
    }

    public function test_add_packings_declaration_appends(): void
    {
        $details = new TransportDetails();
        $details->addPackingsDeclaration(new PackagingDetail(PackagingType::CRATE, 4));

        $this->assertCount(1, $details->getPackingsDeclaration());
        $this->assertSame(PackagingType::CRATE, $details->getPackingsDeclaration()[0]->getPackagingType());
    }
}

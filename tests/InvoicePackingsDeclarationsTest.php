<?php

namespace Tests;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Xml\InvoicesDocReader;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class InvoicePackingsDeclarationsTest extends TestCase
{
    public function test_packages_are_written_inside_one_packings_declarations_element(): void
    {
        $invoice = Invoice::factory()->make()->setPackingsDeclarations($this->packages());

        $declarations = $this->invoiceXml($invoice)->packingsDeclarations;

        $this->assertCount(1, $declarations);
        $this->assertCount(2, $declarations->Packages);
        $this->assertEquals('1', (string) $declarations->Packages[0]->packagingType);
        $this->assertEquals('2', (string) $declarations->Packages[0]->quantity);
        $this->assertEquals('6', (string) $declarations->Packages[1]->packagingType);
        $this->assertEquals('Σακούλα', (string) $declarations->Packages[1]->otherPackagingTypeTitle);
    }

    public function test_add_packaging_declaration_appends_to_the_same_declaration(): void
    {
        $invoice = new Invoice();
        $invoice->addPackagingDeclaration(new PackagingDetail(PackagingType::CRATE, 4));
        $invoice->addPackagingDeclaration(new PackagingDetail(PackagingType::SACK, 1));

        $packages = $invoice->getPackingsDeclarations();

        $this->assertCount(2, $packages);
        $this->assertSame(PackagingType::CRATE, $packages[0]->getPackagingType());
        $this->assertSame(PackagingType::SACK, $packages[1]->getPackagingType());
    }

    public function test_packages_survive_reading_the_xml_back(): void
    {
        $invoice = Invoice::factory()->make()->setPackingsDeclarations($this->packages());

        $read = (new InvoicesDocReader())->parseXML($invoice->toXml(true))->first();
        $packages = $read->getPackingsDeclarations();
        $errors = $read->validate();

        $this->assertCount(2, $packages);
        $this->assertSame(PackagingType::PALLET, $packages[0]->getPackagingType());
        $this->assertEquals(2, $packages[0]->getQuantity());
        $this->assertSame(PackagingType::OTHER, $packages[1]->getPackagingType());
        $this->assertSame('Σακούλα', $packages[1]->getOtherPackagingTypeTitle());
        $this->assertEmpty($errors, 'Validation failed: '.print_r($errors, true));
    }

    public function test_no_packages_writes_no_packings_declarations_element(): void
    {
        $invoice = Invoice::factory()->make();
        $invoice->addPackagingDeclaration(new PackagingDetail(PackagingType::PALLET, 1));
        $invoice->setPackingsDeclarations([]);

        $this->assertNull($invoice->getPackingsDeclarations());
        $this->assertCount(0, $this->invoiceXml($invoice)->packingsDeclarations);
    }

    /**
     * @return PackagingDetail[]
     */
    private function packages(): array
    {
        return [
            new PackagingDetail(PackagingType::PALLET, 2),
            (new PackagingDetail(PackagingType::OTHER, 1))->setOtherPackagingTypeTitle('Σακούλα'),
        ];
    }

    private function invoiceXml(Invoice $invoice): SimpleXMLElement
    {
        return (new SimpleXMLElement($invoice->toXml(true)))->invoice;
    }
}

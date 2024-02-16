<?php

namespace Tests;

use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderOtherCorrelatedEntitiesTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_header_other_correlated_entities_to_xml(): void
    {
        $partyType1 = $this->createParty('123456789', 'GR', 0);
        $entityType1 = $this->createEntity(EntityTypes::TYPE_3, $partyType1);

        $partyType2 = $this->createParty('987654321', 'IT', 1);
        $entityType2 = $this->createEntity(EntityTypes::TYPE_2, $partyType2);

        $header = new InvoiceHeader();
        $header->addOtherCorrelatedEntities($entityType1);
        $header->addOtherCorrelatedEntities($entityType2);

        $invoice = new Invoice();
        $invoice->setInvoiceHeader($header);

        $dom = $this->toXML($invoice);
        $oce = $dom->InvoicesDoc->invoice->invoiceHeader->otherCorrelatedEntities;

        $this->assertCount(2, $oce);
        $this->assertEquals(EntityTypes::TYPE_3->value, $oce[0]->get('type'));
        $this->assertEquals('123456789', $oce[0]->entityData->vatNumber);
        $this->assertEquals('GR', $oce[0]->entityData->country);
        $this->assertEquals(0, $oce[0]->entityData->branch);

        $this->assertCount(2, $oce[1]);
        $this->assertEquals(EntityTypes::TYPE_2->value, $oce[1]->type);
        $this->assertEquals('987654321', $oce[1]->entityData->vatNumber);
        $this->assertEquals('IT', $oce[1]->entityData->country);
        $this->assertEquals(1, $oce[1]->entityData->branch);
    }

    public function test_it_converts_xml_to_invoice_header_other_correlated_entities()
    {
        $invoice = $this->getInvoiceFromXml('requested-doc-complete-invoice');

        $entities = $invoice->getInvoiceHeader()->getOtherCorrelatedEntities();

        $this->assertCount(2, $entities);

        $entity1 = $entities[0];
        $this->assertEquals(EntityTypes::TYPE_3->value, $entity1->getType());
        $this->assertEquals('888888888', $entity1->getEntityData()->getVatNumber());
        $this->assertEquals('GR', $entity1->getEntityData()->getCountry());
        $this->assertEquals(1, $entity1->getEntityData()->getBranch());
        $this->assertEquals('123456', $entity1->getEntityData()->getDocumentIdNo());
        $this->assertEquals('8009988811', $entity1->getEntityData()->getSupplyAccountNo());
        $this->assertEquals('GR', $entity1->getEntityData()->getCountryDocumentId());
        $this->assertEquals('Παπαδόπουλος ΙΚΕ', $entity1->getEntityData()->getName());
        $this->assertEquals('Λεωφόρου Στρατού', $entity1->getEntityData()->getAddress()->getStreet());
        $this->assertEquals('328', $entity1->getEntityData()->getAddress()->getNumber());
        $this->assertEquals('67550', $entity1->getEntityData()->getAddress()->getPostalCode());
        $this->assertEquals('Καβάλα', $entity1->getEntityData()->getAddress()->getCity());

        $entity1 = $entities[1];
        $this->assertEquals(EntityTypes::TYPE_2->value, $entity1->getType());
        $this->assertEquals('IT999999999', $entity1->getEntityData()->getVatNumber());
        $this->assertEquals('IT', $entity1->getEntityData()->getCountry());
        $this->assertEquals(2, $entity1->getEntityData()->getBranch());
        $this->assertEquals('AXS6654', $entity1->getEntityData()->getDocumentIdNo());
        $this->assertEquals('ASDD66655', $entity1->getEntityData()->getSupplyAccountNo());
        $this->assertEquals('IT', $entity1->getEntityData()->getCountryDocumentId());
        $this->assertEquals('Acme Limited', $entity1->getEntityData()->getName());
        $this->assertEquals('Via la Spezia', $entity1->getEntityData()->getAddress()->getStreet());
        $this->assertEquals('553', $entity1->getEntityData()->getAddress()->getNumber());
        $this->assertEquals('00042', $entity1->getEntityData()->getAddress()->getPostalCode());
        $this->assertEquals('Rome', $entity1->getEntityData()->getAddress()->getCity());
    }
}
<?php

namespace Tests;

use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderOtherCorrelatedEntitiesTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_other_correlated_entities_to_xml()
    {
        $invoice = Invoice::factory()->make();

        $entities = $invoice->getInvoiceHeader()->getOtherCorrelatedEntities();
        $entitiesXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader->otherCorrelatedEntities;

        $this->assertCount(2, $entitiesXml);

        // Test other correlated entity 1
        $entityData1 = $entities[0]->getEntityData();
        $entityData1Xml = $entitiesXml[0]->entityData;
        $this->assertEquals($entities[0]->getType()->value, $entitiesXml[0]->type);
        $this->assertEquals($entityData1->getVatNumber(), $entityData1Xml->vatNumber);
        $this->assertEquals($entityData1->getCountry(), $entityData1Xml->country);
        $this->assertEquals($entityData1->getBranch(), $entityData1Xml->branch);
        $this->assertEquals($entityData1->getName(), $entityData1Xml->name);
        $this->assertEquals($entityData1->getAddress()->getStreet(), $entityData1Xml->address->street);
        $this->assertEquals($entityData1->getAddress()->getNumber(), $entityData1Xml->address->number);
        $this->assertEquals($entityData1->getAddress()->getPostalCode(), $entityData1Xml->address->postalCode);
        $this->assertEquals($entityData1->getAddress()->getCity(), $entityData1Xml->address->city);

        // Test other correlated entity 2
        $entityData2 = $entities[1]->getEntityData();
        $entityData2Xml = $entitiesXml[1]->entityData;
        $this->assertEquals($entities[1]->getType()->value, $entitiesXml[1]->type);
        $this->assertEquals($entityData2->getVatNumber(), $entityData2Xml->vatNumber);
        $this->assertEquals($entityData2->getCountry(), $entityData2Xml->country);
        $this->assertEquals($entityData2->getBranch(), $entityData2Xml->branch);
        $this->assertEquals($entityData2->getName(), $entityData2Xml->name);
        $this->assertEquals($entityData2->getAddress()->getStreet(), $entityData2Xml->address->street);
        $this->assertEquals($entityData2->getAddress()->getNumber(), $entityData2Xml->address->number);
        $this->assertEquals($entityData2->getAddress()->getPostalCode(), $entityData2Xml->address->postalCode);
        $this->assertEquals($entityData2->getAddress()->getCity(), $entityData2Xml->address->city);
    }

    public function test_it_converts_xml_other_correlated_entities()
    {
        $header = $this->getInvoiceFromXml()->getInvoiceHeader();

        $this->assertCount(2, $header->getOtherCorrelatedEntities());

        // Testing other correlated entity 1
        $entity1 = $header->getOtherCorrelatedEntities()[0];
        $this->assertEquals(EntityTypes::TYPE_3, $entity1->getType());
        $this->assertEquals('888888888', $entity1->getEntityData()->getVatNumber());
        $this->assertEquals('GR', $entity1->getEntityData()->getCountry());
        $this->assertEquals(1, $entity1->getEntityData()->getBranch());
        $this->assertEquals('Παπαδόπουλος ΙΚΕ', $entity1->getEntityData()->getName());
        $this->assertEquals('Λεωφόρου Στρατού', $entity1->getEntityData()->getAddress()->getStreet());
        $this->assertEquals('328', $entity1->getEntityData()->getAddress()->getNumber());
        $this->assertEquals('67550', $entity1->getEntityData()->getAddress()->getPostalCode());
        $this->assertEquals('Καβάλα', $entity1->getEntityData()->getAddress()->getCity());

        // Testing other correlated entity 2
        $entity2 = $header->getOtherCorrelatedEntities()[1];
        $this->assertEquals(EntityTypes::TYPE_2, $entity2->getType());
        $this->assertEquals('IT999999999', $entity2->getEntityData()->getVatNumber());
        $this->assertEquals('IT', $entity2->getEntityData()->getCountry());
        $this->assertEquals(2, $entity2->getEntityData()->getBranch());
        $this->assertEquals('Acme Limited', $entity2->getEntityData()->getName());
        $this->assertEquals('Via la Spezia', $entity2->getEntityData()->getAddress()->getStreet());
        $this->assertEquals('553', $entity2->getEntityData()->getAddress()->getNumber());
        $this->assertEquals('00042', $entity2->getEntityData()->getAddress()->getPostalCode());
        $this->assertEquals('Rome', $entity2->getEntityData()->getAddress()->getCity());
    }
}
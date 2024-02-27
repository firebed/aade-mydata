<?php

namespace Tests;

use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\EntityType;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;
use Firebed\AadeMyData\Models\Party;
use Firebed\AadeMyData\Models\PaymentMethods;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceSimilarityTest extends TestCase
{
    use HandlesInvoiceXml;
    
    public function test_invoices_are_identical()
    {
        $invoiceFromXml = $this->getInvoiceFromXml();
        
        $invoice = new Invoice();
        $invoice->setIssuer($this->createIssuer());
        $invoice->setCounterpart($this->createCounterpart());
        $invoice->setInvoiceHeader($this->createInvoiceHeader());

        $this->assertEquals($invoice->getIssuer(), $invoiceFromXml->getIssuer());
        $this->assertEquals($invoice->getCounterpart(), $invoiceFromXml->getCounterpart());
        $this->assertEquals($invoice->getInvoiceHeader(), $invoiceFromXml->getInvoiceHeader());
        
    }

    private function createIssuer(): Issuer
    {
        $address = new Address();
        $address->setStreet('28ης Οκτωβρίου');
        $address->setNumber('54A');
        $address->setPostalCode('44458');
        $address->setCity('44458');
        $address->setCity('Ηράκλειο');
        
        $issuer = new Issuer();
        $issuer->setVatNumber('888888888');
        $issuer->setCountry('GR');
        $issuer->setBranch(1);
        $issuer->setName('Ακμή ΑΕ');
        $issuer->setAddress($address);
        $issuer->setDocumentIdNo('AAA5454');
        $issuer->setSupplyAccountNo('7845547781');
        $issuer->setCountryDocumentId('GR');
        
        return $issuer;
    }

    private function createCounterpart(): Counterpart
    {
        $address = new Address();
        $address->setStreet('Τσιμισκή');
        $address->setNumber('52A');
        $address->setPostalCode('33333');
        $address->setCity('Χανιά');

        $counterpart = new Counterpart();
        $counterpart->setVatNumber('999999999');
        $counterpart->setCountry('GR');
        $counterpart->setBranch(0);
        $counterpart->setName('Παπαδόπουλος ΑΕ');
        $counterpart->setAddress($address);
        $counterpart->setDocumentIdNo('MMM123N');
        $counterpart->setSupplyAccountNo('809778544');
        $counterpart->setCountryDocumentId('GR');

        return $counterpart;
    }

    private function createInvoiceHeader(): InvoiceHeader
    {
        $invoiceHeader = new InvoiceHeader();
        $invoiceHeader->setSeries('A');
        $invoiceHeader->setAA(101);
        $invoiceHeader->setIssueDate('2020-04-08');
        $invoiceHeader->setInvoiceType(InvoiceType::TYPE_1_1);
        $invoiceHeader->setVatPaymentSuspension(false);
        $invoiceHeader->setCurrency('EUR');
        $invoiceHeader->setExchangeRate(12.345678);
        $invoiceHeader->addCorrelatedInvoice(8000000165487234);
        $invoiceHeader->addCorrelatedInvoice(8000000165487568);
        $invoiceHeader->addCorrelatedInvoice(8000000165487101);
        $invoiceHeader->setSelfPricing(true);
        $invoiceHeader->setDispatchDate('2024-02-13');
        $invoiceHeader->setDispatchTime('00:00');
        $invoiceHeader->setVehicleNumber('KHB4201');
        $invoiceHeader->setMovePurpose(MovePurpose::TYPE_19);
        $invoiceHeader->setFuelInvoice(true);
        $invoiceHeader->setSpecialInvoiceCategory(SpecialInvoiceCategory::TYPE_5);
        $invoiceHeader->setInvoiceVariationType(InvoiceVariationType::TYPE_3);
        $invoiceHeader->addOtherCorrelatedEntities($this->createCorrelatedEntity1());
        $invoiceHeader->addOtherCorrelatedEntities($this->createCorrelatedEntity2());
        $invoiceHeader->setOtherDeliveryNoteHeader($this->createOtherDeliveryNoteHeader());
        $invoiceHeader->setIsDeliveryNote(true);
        $invoiceHeader->setOtherMovePurposeTitle('Έκθεση');
        $invoiceHeader->setThirdPartyCollection(true);
        return $invoiceHeader;
    }

    private function createCorrelatedEntity1(): EntityType
    {
        $entity = new EntityType();
        $entity->setType(EntityTypes::TYPE_3);
        $entity->setEntityData(new Party());
        $entity->getEntityData()->setVatNumber('888888888');
        $entity->getEntityData()->setCountry('GR');
        $entity->getEntityData()->setBranch(1);
        $entity->getEntityData()->setName('Παπαδόπουλος ΙΚΕ');
        $entity->getEntityData()->setAddress(new Address());
        $entity->getEntityData()->getAddress()->setStreet('Λεωφόρου Στρατού');
        $entity->getEntityData()->getAddress()->setNumber('328');
        $entity->getEntityData()->getAddress()->setPostalCode('67550');
        $entity->getEntityData()->getAddress()->setCity('Καβάλα');
        $entity->getEntityData()->setDocumentIdNo('123456');
        $entity->getEntityData()->setSupplyAccountNo('8009988811');
        $entity->getEntityData()->setCountryDocumentId('GR');
        
        return $entity;
    }
    
    private function createCorrelatedEntity2(): EntityType
    {
        $entity = new EntityType();
        $entity->setType(EntityTypes::TYPE_2);
        $entity->setEntityData(new Party());
        $entity->getEntityData()->setVatNumber('IT999999999');
        $entity->getEntityData()->setCountry('IT');
        $entity->getEntityData()->setBranch(2);
        $entity->getEntityData()->setName('Acme Limited');
        $entity->getEntityData()->setAddress(new Address());
        $entity->getEntityData()->getAddress()->setStreet('Via la Spezia');
        $entity->getEntityData()->getAddress()->setNumber('553');
        $entity->getEntityData()->getAddress()->setPostalCode('00042');
        $entity->getEntityData()->getAddress()->setCity('Rome');
        $entity->getEntityData()->setDocumentIdNo('AXS6654');
        $entity->getEntityData()->setSupplyAccountNo('ASDD66655');
        $entity->getEntityData()->setCountryDocumentId('IT');

        return $entity;
    }

    private function createOtherDeliveryNoteHeader(): OtherDeliveryNoteHeader
    {
        $header = new OtherDeliveryNoteHeader();
        $header->setLoadingAddress(new Address());
        $header->getLoadingAddress()->setStreet('Τσιμισκή');
        $header->getLoadingAddress()->setNumber('25');
        $header->getLoadingAddress()->setPostalCode('13152');
        $header->getLoadingAddress()->setCity('Θεσσαλονίκη');
        $header->setDeliveryAddress(new Address());
        
        $header->getDeliveryAddress()->setStreet('Παπανδρέου');
        $header->getDeliveryAddress()->setNumber('52');
        $header->getDeliveryAddress()->setPostalCode('11255');
        $header->getDeliveryAddress()->setCity('Αθήνα');
        
        $header->setStartShippingBranch(1);
        $header->setCompleteShippingBranch(2);
        
        return $header;
    }

    private function createPaymentMethods(): PaymentMethods
    {
        
    }
}
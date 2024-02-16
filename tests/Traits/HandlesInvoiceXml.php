<?php

namespace Tests\Traits;

use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\PaymentMethod;
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\EntityType;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Party;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Xml\InvoicesDocReader;
use Firebed\AadeMyData\Xml\RequestedDocReader;
use Tests\Xml\Document;
use Tests\Xml\Node;

trait HandlesInvoiceXml
{
    use UsesStubs;

    public function getInvoiceFromXml(string $filename = 'request-doc-response'): Invoice
    {
        $xmlString = $this->getStub($filename);

        $parser = new RequestedDocReader();
        $requestedDoc = $parser->parseXML($xmlString);

        return $requestedDoc->getInvoicesDoc()->get(0);
    }

    public function getRequestedDocFromXml(string $filename = 'request-doc-response'): RequestedDoc
    {
        $xmlString = $this->getStub($filename);
        $parser = new RequestedDocReader();

        return $parser->parseXML($xmlString);
    }

    public function createAddress(string $street, string $number, string $postalCode, string $city): Address
    {
        $address = new Address();
        $address->setPostalCode($postalCode);
        $address->setCity($city);

        if ($street) {
            $address->setStreet($street);
        }

        if ($number) {
            $address->setNumber($number);
        }

        return $address;
    }

    public function createParty(string $vatNumber, string $country, int $branch, string $name = null, Address $address = null, string $documentIdNo = null, string $supplyAccountNo = null, string $countryDocumentId = null): Party
    {
        $party = new Party();
        $party->setVatNumber($vatNumber);
        $party->setCountry($country);
        $party->setBranch($branch);
        $party->setName($name);
        $party->setAddress($address);
        $party->setDocumentIdNo($documentIdNo);
        $party->setSupplyAccountNo($supplyAccountNo);
        $party->setCountryDocumentId($countryDocumentId);

        return $party;
    }

    public function createEntity(EntityTypes $type, Party $party): EntityType
    {
        $entity = new EntityType();
        $entity->setType($type);
        $entity->setEntityData($party);

        return $entity;
    }

    public function createPaymentMethodDetail(PaymentMethod $type, float $amount, string $info = null, float $tipAmount = null, string $transactionId = null): PaymentMethodDetail
    {
        $paymentMethod = new PaymentMethodDetail();
        $paymentMethod->setType($type);
        $paymentMethod->setAmount($amount);
        $paymentMethod->setPaymentMethodInfo($info);
        $paymentMethod->setTipAmount($tipAmount);
        $paymentMethod->setTransactionId($transactionId);
        return $paymentMethod;
    }

    public function assertIncomeClassification(IncomeClassificationType $expectedType, IncomeClassificationCategory $expectedCategory, float $expectedAmount, IncomeClassification|Node $source): void
    {
        if ($source instanceof Node) {
            $this->assertEquals($expectedType->value, $source['icls:classificationType']);
            $this->assertEquals($expectedCategory->value, $source['icls:classificationCategory']);
            $this->assertEquals($expectedAmount, $source['icls:amount']);
            return;
        }

        $this->assertEquals($expectedType->value, $source->getClassificationType());
        $this->assertEquals($expectedCategory->value, $source->getClassificationCategory());
        $this->assertEquals($expectedAmount, $source->getAmount());
    }

    public function assertExpensesClassification(ExpenseClassificationType $expectedType, ExpenseClassificationCategory $expectedCategory, float $expectedAmount, ExpensesClassification|Node $source): void
    {
        if ($source instanceof Node) {
            $this->assertEquals($expectedType->value, $source['ecls:classificationType']);
            $this->assertEquals($expectedCategory->value, $source['ecls:classificationCategory']);
            $this->assertEquals($expectedAmount, $source['ecls:amount']);
            return;
        }

        $this->assertEquals($expectedType->value, $source->getClassificationType());
        $this->assertEquals($expectedCategory->value, $source->getClassificationCategory());
        $this->assertEquals($expectedAmount, $source->getAmount());
    }

    protected function toXML(Invoice $invoice): object
    {
        $invoicesDoc = new InvoicesDoc();
        $invoicesDoc->addInvoice($invoice);

        $xmlString = (new InvoicesDocReader())->asXML($invoicesDoc);

        return new Document($xmlString);
    }
}
<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Exceptions\SchemaValidationException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\FakeGateway;
use Firebed\AadeMyData\Xml\Schema;
use PHPUnit\Framework\TestCase;

class RequestValidationTest extends TestCase
{
    private FakeGateway $fake;

    protected function setUp(): void
    {
        $this->fake = MyData::fake();
    }

    protected function tearDown(): void
    {
        MyData::stopFaking();
        MyDataRequest::setDefaultConnection(null);
    }

    private function validating(): MyData
    {
        return new MyData(Connection::make('u', 'k')->withValidation());
    }

    private function invalidInvoice(): Invoice
    {
        $invoice = Invoice::factory()->make();
        $invoice->getInvoiceSummary()->setTotalGrossValue(-10);

        return $invoice;
    }

    public function test_a_valid_document_is_sent(): void
    {
        $this->validating()->sendInvoices(Invoice::factory()->make());

        $this->fake->assertSentCount(1, SendInvoices::class);
    }

    public function test_an_invalid_document_is_not_sent(): void
    {
        try {
            $this->validating()->sendInvoices([Invoice::factory()->make(), $this->invalidInvoice()]);
            $this->fail('No SchemaValidationException was thrown.');
        } catch (SchemaValidationException $e) {
            $this->assertSame('InvoicesDoc', $e->getSchema());
            $this->assertSame('{http://www.aade.gr/myDATA/invoice/v1.0}totalGrossValue', $e->getErrors()[0]['field']);
            $this->assertStringContainsString("totalGrossValue: The value '-10' is less than the minimum value allowed ('0').", $e->getMessage());
        }

        $this->fake->assertNothingSent();
    }

    public function test_documents_are_not_validated_by_default(): void
    {
        (new MyData(Connection::make('u', 'k')))->sendInvoices($this->invalidInvoice());

        $this->fake->assertSentCount(1);
    }

    public function test_every_document_request_is_validated_against_its_own_schema(): void
    {
        $payment = (new PaymentMethod)->setInvoiceMark(400001)->addPaymentMethodDetails((new PaymentMethodDetail)->setType(PaymentType::POS)->setAmount(10));
        $this->validating()->sendPaymentsMethod($payment);

        $this->expectException(SchemaValidationException::class);
        $this->expectExceptionMessage('paymentMethods');

        $this->validating()->sendPaymentsMethod((new PaymentMethod)->setInvoiceMark(400001));
    }

    public function test_requests_without_a_document_are_not_affected(): void
    {
        $this->validating()->cancelInvoice(400001);

        $this->fake->assertSentCount(1);
    }

    public function test_malformed_xml_is_reported_as_errors_without_a_warning(): void
    {
        $errors = Schema::validate('<InvoicesDoc', 'InvoicesDoc');

        $this->assertGreaterThan(1, count($errors));
        $this->assertStringContainsString('end of Start Tag', $errors[0]['message']);
    }

    public function test_the_default_connection_can_validate(): void
    {
        MyDataRequest::setValidation();

        $this->assertTrue(MyDataRequest::defaultConnection()->validatesRequests());

        $this->expectException(SchemaValidationException::class);
        (new SendInvoices)->handle($this->invalidInvoice());
    }
}

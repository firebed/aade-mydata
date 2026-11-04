<?php

declare(strict_types=1);

namespace Tests\Testing;

use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Http\CancelDeliveryNote;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Models\ContinuationToken;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\FakeGateway;
use Firebed\AadeMyData\Testing\RecordedRequest;
use LogicException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Tests\Http\RecordingGateway;

class FakeGatewayTest extends TestCase
{
    private MyData $mydata;

    protected function setUp(): void
    {
        // No credentials: a faked request never reaches the transport
        $this->mydata = new MyData(Connection::make('', ''));
    }

    protected function tearDown(): void
    {
        MyData::stopFaking();
        MyDataRequest::setDefaultConnection(null);
    }

    public function test_sent_invoices_succeed_with_a_mark_and_uid_each(): void
    {
        $fake = MyData::fake();
        $invoices = [Invoice::factory()->make(), Invoice::factory()->make()];

        $responses = $this->mydata->sendInvoices($invoices)->all();

        $this->assertCount(2, $responses);
        $this->assertSame([1, 2], [$responses[0]->getIndex(), $responses[1]->getIndex()]);
        $this->assertTrue($responses[0]->isSuccessful());
        $this->assertSame('400000000000001', $responses[0]->getInvoiceMark());
        $this->assertSame('400000000000002', $responses[1]->getInvoiceMark());
        $this->assertSame($invoices[1]->guessUid(), $responses[1]->getInvoiceUid());
        $this->assertNotNull($responses[0]->getAuthenticationCode());

        $fake->assertSentCount(1, SendInvoices::class);
    }

    public function test_a_rejected_response_carries_the_error_and_no_mark(): void
    {
        MyData::fake([SendInvoices::class => FakeGateway::rejected(101, 'Invalid VAT number')]);

        $response = $this->mydata->sendInvoices(Invoice::factory()->make())->first();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('ValidationError', $response->getStatusCode());
        $this->assertNull($response->getInvoiceMark());
        $this->assertSame('101', $response->getErrors()->first()->getCode());
        $this->assertSame('Invalid VAT number', $response->getErrors()->first()->getMessage());
    }

    public function test_a_list_of_responses_answers_one_per_invoice(): void
    {
        MyData::fake([SendInvoices::class => [FakeGateway::accepted(), FakeGateway::rejected(102, 'Wrong total')]]);

        [$accepted, $rejected] = $this->mydata->sendInvoices([Invoice::factory()->make(), Invoice::factory()->make()])->all();

        $this->assertSame('400000000000001', $accepted->getInvoiceMark());
        $this->assertSame(2, $rejected->getIndex());
        $this->assertNull($rejected->getInvoiceMark());
    }

    public function test_an_accepted_response_keeps_the_mark_it_is_given(): void
    {
        MyData::fake([SendInvoices::class => FakeGateway::accepted(400001234567890, 'ABC')]);

        $response = $this->mydata->sendInvoices(Invoice::factory()->make())->first();

        $this->assertSame('400001234567890', $response->getInvoiceMark());
        $this->assertSame('ABC', $response->getInvoiceUid());
    }

    public function test_a_registered_response_serves_every_request_afresh(): void
    {
        MyData::fake([SendInvoices::class => FakeGateway::accepted()]);

        $first = $this->mydata->sendInvoices(Invoice::factory()->make())->first();
        $second = $this->mydata->sendInvoices(Invoice::factory()->make())->first();

        $this->assertSame('400000000000001', $first->getInvoiceMark());
        $this->assertSame('400000000000002', $second->getInvoiceMark());
    }

    public function test_the_request_class_wins_over_a_parent_given_before_it(): void
    {
        MyData::fake([
            CancelInvoice::class => FakeGateway::accepted(),
            CancelDeliveryNote::class => FakeGateway::rejected(301, 'Not a delivery note'),
        ]);

        $this->assertTrue($this->mydata->cancelInvoice(1)->first()->isSuccessful());
        $this->assertFalse($this->mydata->with(fn (Connection $c) => $c->asProvider())->cancelDeliveryNote(1)->first()->isSuccessful());
    }

    public function test_payment_methods_and_cancellations_get_their_own_marks(): void
    {
        MyData::fake();

        $payment = (new PaymentMethod)->setInvoiceMark(400001)->addPaymentMethodDetails((new PaymentMethodDetail)->setType(PaymentType::POS)->setAmount(10));

        $this->assertSame('400000000000001', $this->mydata->sendPaymentsMethod($payment)->first()->getPaymentMethodMark());
        $this->assertSame('400000000000002', $this->mydata->cancelInvoice(400001)->first()->getCancellationMark());
    }

    public function test_searches_find_nothing_by_default(): void
    {
        MyData::fake();

        $this->assertNull($this->mydata->requestDocs('0')->getInvoices());
        $this->assertNull($this->mydata->requestVatInfo('01/01/2026', '31/01/2026')->getVatInfo());
    }

    public function test_a_list_of_invoices_is_answered_as_a_requested_doc(): void
    {
        $invoice = Invoice::factory()->make();
        MyData::fake([RequestDocs::class => [$invoice]]);

        $found = $this->mydata->requestDocs('0')->getInvoices();

        $this->assertCount(1, $found);
        $this->assertSame($invoice->getInvoiceHeader()->getAa(), $found->first()->getInvoiceHeader()->getAa());
    }

    public function test_a_sequence_answers_the_pages_in_order(): void
    {
        $first = new RequestedDoc([
            'invoicesDoc' => new InvoicesDoc([Invoice::factory()->make()]),
            'continuationToken' => new ContinuationToken(['nextPartitionKey' => 'p', 'nextRowKey' => 'r']),
        ]);
        $last = new RequestedDoc(['invoicesDoc' => new InvoicesDoc([Invoice::factory()->make()])]);

        $fake = MyData::fake([RequestDocs::class => FakeGateway::sequence($first, $last)]);

        $invoices = iterator_to_array((new RequestDocs)->invoices('0'), false);

        $this->assertCount(2, $invoices);
        $fake->assertSentCount(2, RequestDocs::class);
        $fake->assertSent(RequestDocs::class, fn (RequestDocs $request, RecordedRequest $sent) => ($sent->query['nextPartitionKey'] ?? null) === 'p');
    }

    public function test_a_sequence_that_runs_out_fails_unless_it_has_a_fallback(): void
    {
        MyData::fake([RequestVatInfo::class => FakeGateway::sequence('<RequestedVatInfo/>')->whenEmpty(new RateLimitExceededException(retryAfter: 300))]);

        $this->mydata->requestVatInfo('01/01/2026', '31/01/2026');

        try {
            $this->mydata->requestVatInfo('01/01/2026', '31/01/2026');
            $this->fail('The fallback exception was not thrown.');
        } catch (RateLimitExceededException $e) {
            $this->assertSame(300, $e->getRetryAfter());
        }

        MyData::fake([RequestVatInfo::class => FakeGateway::sequence()]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('ran out of responses');

        $this->mydata->requestVatInfo('01/01/2026', '31/01/2026');
    }

    public function test_a_closure_receives_the_request(): void
    {
        MyData::fake([
            SendInvoices::class => fn (SendInvoices $request) => array_map(
                fn (Invoice $invoice) => $invoice->getInvoiceHeader()->getAa() === '13' ? FakeGateway::rejected(101, 'Duplicate') : FakeGateway::accepted(),
                $request->getInvoicesDoc()->all(),
            ),
        ]);

        $invoice = Invoice::factory()->make();
        $invoice->getInvoiceHeader()->setAa('13');

        $this->assertFalse($this->mydata->sendInvoices($invoice)->first()->isSuccessful());
    }

    public function test_the_wildcard_answers_any_request_and_a_class_wins_over_it(): void
    {
        MyData::fake([
            CancelInvoice::class => FakeGateway::accepted(),
            '*' => new RateLimitExceededException(retryAfter: 60),
        ]);

        $this->assertNotNull($this->mydata->cancelInvoice(1)->first()->getCancellationMark());

        $this->expectException(RateLimitExceededException::class);
        $this->mydata->sendInvoices(Invoice::factory()->make());
    }

    public function test_requests_without_a_default_need_a_response(): void
    {
        MyData::fake();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No fake response for RegisterTransfer');

        $this->mydata->registerTransfer(new Transport);
    }

    public function test_assertions(): void
    {
        $fake = MyData::fake();
        $fake->assertNothingSent();

        $this->mydata->sendInvoices(Invoice::factory()->make());

        $fake->assertSent(SendInvoices::class);
        $fake->assertSent(SendInvoices::class, fn (SendInvoices $request, RecordedRequest $sent) => $sent->count() === 1 && $sent->method === 'POST');
        $fake->assertNotSent(CancelInvoice::class);
        $fake->assertNotSent(SendInvoices::class, fn (SendInvoices $request, RecordedRequest $sent) => $sent->count() === 2);
        $fake->assertSentCount(1);
        $this->assertStringContainsString('<InvoicesDoc', $fake->recorded()[0]->body);

        $this->expectException(AssertionFailedError::class);
        $fake->assertNothingSent();
    }

    public function test_assert_sent_fails_when_nothing_matches(): void
    {
        $fake = MyData::fake();

        $this->expectException(AssertionFailedError::class);
        $fake->assertSent(SendPaymentsMethod::class);
    }

    public function test_the_fake_wins_over_the_connection_and_per_request_gateways(): void
    {
        $gateway = new RecordingGateway;
        $fake = MyData::fake();

        $this->mydata->with(fn (Connection $c) => $c->withGateway($gateway))->cancelInvoice(1);
        (new CancelInvoice)->usingGateway($gateway)->handle(2);

        $this->assertNull($gateway->request);
        $fake->assertSentCount(2, CancelInvoice::class);
    }

    public function test_stop_faking_sends_requests_again(): void
    {
        MyData::fake();
        MyData::stopFaking();

        $gateway = new RecordingGateway;
        $this->mydata->with(fn (Connection $c) => $c->withGateway($gateway))->cancelInvoice(1);

        $this->assertInstanceOf(CancelInvoice::class, $gateway->request);
    }
}

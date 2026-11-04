<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\SendUnsignedInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\FakeGateway;

class SendUnsignedInvoicesTest extends MyDataHttpTestCase
{
    protected function tearDown(): void
    {
        MyData::stopFaking();
    }

    public function test_it_sends_the_invoices_doc_to_its_own_action(): void
    {
        $fake = MyData::fake();
        $invoice = Invoice::factory()->make();

        $response = (new MyData(Connection::make('u', 'k', 'prod', provider: true)))->sendUnsignedInvoices($invoice)->first();

        $sent = $fake->recorded(SendUnsignedInvoices::class)[0];
        $this->assertSame('https://mydatapi.aade.gr/myDataProvider/SendUnsignedInvoices', $sent->request->getUrl());
        $this->assertStringContainsString('<InvoicesDoc', $sent->body);

        // As the sandbox answers: a mark, the uid, no authentication code
        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getInvoiceMark());
        $this->assertSame($invoice->guessUid(), $response->getInvoiceUid());
        $this->assertNull($response->getAuthenticationCode());

        // Another myDATA action: SendInvoices responses and assertions do not cover it
        $fake->assertNotSent(SendInvoices::class);
        $fake->assertSentCount(1, SendUnsignedInvoices::class);
    }

    public function test_a_send_invoices_response_does_not_answer_unsigned_invoices(): void
    {
        MyData::fake([SendInvoices::class => FakeGateway::rejected(101, 'Signed only')]);
        $provider = new MyData(Connection::make('u', 'k', 'dev', provider: true));

        $this->assertFalse($provider->sendInvoices(Invoice::factory()->make())->first()->isSuccessful());
        $this->assertTrue($provider->sendUnsignedInvoices(Invoice::factory()->make())->first()->isSuccessful());
    }

    public function test_it_is_provider_only(): void
    {
        $this->expectException(UnsupportedChannelException::class);

        (new MyData(Connection::make('u', 'k')))->sendUnsignedInvoices(Invoice::factory()->make());
    }

    public function test_a_request_on_the_wrong_channel_keeps_no_invoices(): void
    {
        $request = (new SendUnsignedInvoices)->using(Connection::make('u', 'k'));

        try {
            $request->handle(Invoice::factory()->make());
            $this->fail('No UnsupportedChannelException was thrown.');
        } catch (UnsupportedChannelException) {
        }

        $this->assertNull($request->getInvoicesDoc());
    }
}

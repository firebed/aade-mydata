<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\SendUnsignedInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\RecordedRequest;

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

        // An invoice that was not issued gets no mark
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getInvoiceMark());
        $this->assertSame($invoice->guessUid(), $response->getInvoiceUid());

        $fake->assertNotSent(SendInvoices::class, fn (SendInvoices $r, RecordedRequest $s) => ! $r instanceof SendUnsignedInvoices);
    }

    public function test_it_is_provider_only(): void
    {
        $this->expectException(UnsupportedChannelException::class);

        (new MyData(Connection::make('u', 'k')))->sendUnsignedInvoices(Invoice::factory()->make());
    }
}

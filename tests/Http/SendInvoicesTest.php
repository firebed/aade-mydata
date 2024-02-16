<?php

namespace Tests\Http;

use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class SendInvoicesTest extends TestCase
{
    use UsesStubs;

    protected function setUp(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
    }
    
    /**
     * @throws GuzzleException
     */
    public function test_it_sends_invoices()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('send-invoices-response')),
        ]));

        $invoicesDoc = new InvoicesDoc();
        $invoicesDoc->addInvoice(new Invoice());
        $invoicesDoc->addInvoice(new Invoice());
        $invoicesDoc->addInvoice(new Invoice());
        $invoicesDoc->addInvoice(new Invoice());
        $invoicesDoc->addInvoice(new Invoice());

        $sendInvoices = new SendInvoices();
        $responseDoc = $sendInvoices->handle($invoicesDoc);

        $this->assertCount(1, $responseDoc);
        $this->assertEquals(1, $responseDoc[0]->getIndex());
        $this->assertNotEmpty($responseDoc[0]->getInvoiceMark());
        $this->assertNotEmpty($responseDoc[0]->getInvoiceUid());
        $this->assertNotEmpty($responseDoc[0]->getQrUrl());
        $this->assertEquals('Success', $responseDoc[0]->getStatusCode());
    }
}
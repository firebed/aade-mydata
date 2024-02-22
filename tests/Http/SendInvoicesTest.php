<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Factories\ResponseDocXmlFactory;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;

class SendInvoicesTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
     */
    public function test_it_sends_single_invoice()
    {
        $fakeResponse = new ResponseDocXmlFactory();
        $fakeResponse->addResponse(Response::factory()->make());

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $fakeResponse->asXML()),
        ]));

        $sendInvoices = new SendInvoices();
        $responseDoc = $sendInvoices->handle(new Invoice());

        $this->assertCount(1, $responseDoc);
    }
    
    /**
     * @throws MyDataException
     */
    public function test_it_sends_invoices_using_invoices_doc()
    {
        $fakeResponse = new ResponseDocXmlFactory();
        $fakeResponse->addResponse(Response::factory()->make());
        $fakeResponse->addResponse(Response::factory()->make());
        $fakeResponse->addResponse(Response::factory()->make());
        $fakeResponse->addResponse(Response::factory()->make());
        $fakeResponse->addResponse(Response::factory()->make());

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $fakeResponse->asXML()),
        ]));

        $invoicesDoc = new InvoicesDoc([new Invoice(), new Invoice()]);
        $invoicesDoc->add(new Invoice());
        $invoicesDoc->add(new Invoice());
        $invoicesDoc->add(new Invoice());

        $sendInvoices = new SendInvoices();
        $responseDoc = $sendInvoices->handle($invoicesDoc);

        $this->assertCount(5, $responseDoc);
    }

    /**
     * @throws MyDataException
     */
    public function test_it_sends_invoices_using_invoices_array()
    {
        $fakeResponse = new ResponseDocXmlFactory();
        $fakeResponse->addResponse(Response::factory()->make());
        $fakeResponse->addResponse(Response::factory()->make());
        $fakeResponse->addResponse(Response::factory()->make());

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $fakeResponse->asXML()),
        ]));

        $invoices = [new Invoice(), new Invoice(), new Invoice()];

        $sendInvoices = new SendInvoices();
        $responseDoc = $sendInvoices->handle($invoices);

        $this->assertCount(3, $responseDoc);
    }

    /**
     * @throws MyDataException
     */
    public function test_it_sends_a_single_invoice()
    {
        $fakeResponse = new ResponseDocXmlFactory();
        $fakeResponse->addResponse(Response::factory()->make());

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $fakeResponse->asXML()),
        ]));

        $sendInvoices = new SendInvoices();
        $responseDoc = $sendInvoices->handle(new Invoice());

        $this->assertCount(1, $responseDoc);
    }
}
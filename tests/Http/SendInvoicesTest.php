<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\MyDataTimeoutException;
use Firebed\AadeMyData\Exceptions\TransmissionFailedException;
use Firebed\AadeMyData\Factories\ResponseDocXmlFactory;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as HttpResponse;
use ReflectionClass;

class SendInvoicesTest extends MyDataHttpTestCase
{
    public function test_dev_erp_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
        $sendInvoices = new SendInvoices();
        $this->assertEquals('https://mydataapidev.aade.gr/SendInvoices', $sendInvoices->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod');
        $sendInvoices = new SendInvoices();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/SendInvoices', $sendInvoices->getUrl());
    }

    public function test_dev_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $sendInvoices = new SendInvoices();
        $this->assertEquals('https://mydataapidev.aade.gr/myDataProvider/SendInvoices', $sendInvoices->getUrl());
    }

    public function test_prod_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        $sendInvoices = new SendInvoices();
        $this->assertEquals('https://mydatapi.aade.gr/myDataProvider/SendInvoices', $sendInvoices->getUrl());
    }

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
        $invoicesDoc->add(Invoice::make());
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

    /**
     * @throws MyDataException
     */
    public function test_transmission_failure_throws_exception()
    {
        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(500),
        ]));

        $this->expectException(TransmissionFailedException::class);

        $sendInvoices = new SendInvoices();
        $sendInvoices->handle(new Invoice());
    }

    /**
     * Test operation timeout (error 28)
     * @throws MyDataException
     */
    public function test_operation_timeout_throws_exception()
    {
        MyDataRequest::setHandler(new MockHandler([
            new RequestException(
                'Operation timed out',
                new Request('POST', 'test'),
                null,
                null,
                ['errno' => 28]
            )
        ]));

        $this->expectException(MyDataTimeoutException::class);

        $sendInvoices = new SendInvoices();
        $sendInvoices->handle(new Invoice());
    }

    /**
     * Test that timeouts are properly configured
     */
    public function test_timeout_configuration()
    {
        // Reset any previous configuration
        MyDataRequest::setRequestOptions([]);

        // Configure timeouts
        MyDataRequest::setConnectionTimeout(5);
        MyDataRequest::setTimeout(30);

        // Create a reflection class to access private properties
        $reflection = new ReflectionClass(MyDataRequest::class);
        $property = $reflection->getProperty('request_options');
        $options = $property->getValue();

        $this->assertEquals(5, $options['connect_timeout']);
        $this->assertEquals(30, $options['timeout']);
    }
}
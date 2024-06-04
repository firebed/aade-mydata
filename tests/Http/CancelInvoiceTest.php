<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class CancelInvoiceTest extends MyDataHttpTestCase
{
    public function test_dev_erp_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
        $sendInvoices = new CancelInvoice();
        $this->assertEquals('https://mydataapidev.aade.gr/CancelInvoice', $sendInvoices->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod');
        $sendInvoices = new CancelInvoice();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/CancelInvoice', $sendInvoices->getUrl());
    }

    public function test_dev_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $sendInvoices = new CancelInvoice();
        $this->assertEquals('https://mydataapidev.aade.gr/myDataProvider/CancelInvoice', $sendInvoices->getUrl());
    }

    public function test_prod_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        $sendInvoices = new CancelInvoice();
        $this->assertEquals('https://mydatapi.aade.gr/myDataProvider/CancelInvoice', $sendInvoices->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_invoice_is_cancelled()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('cancel-invoice-response')),
        ]));

        $cancelInvoice = new CancelInvoice();
        $responseDoc = $cancelInvoice->handle('400008989888809');

        $this->assertCount(1, $responseDoc);
        $this->assertEquals('Success', $responseDoc[0]->getStatusCode());
        $this->assertEquals('Success', $responseDoc->offsetGet(0)->getStatusCode());
        $this->assertEquals('Success', $responseDoc->first()->getStatusCode());
    }
}
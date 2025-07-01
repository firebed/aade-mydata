<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\CancelDeliveryNote;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class CancelDeliveryNoteTest extends MyDataHttpTestCase
{
    public function test_dev_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $sendInvoices = new CancelDeliveryNote();
        $this->assertEquals('https://mydataapidev.aade.gr/myDataProvider/CancelDeliveryNote', $sendInvoices->getUrl());
    }

    public function test_prod_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        $sendInvoices = new CancelDeliveryNote();
        $this->assertEquals('https://mydatapi.aade.gr/myDataProvider/CancelDeliveryNote', $sendInvoices->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_delivery_note_is_cancelled()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('cancel-delivery-note-response')),
        ]));

        $cancelInvoice = new CancelDeliveryNote();
        $responseDoc = $cancelInvoice->handle('400008989888809');

        $this->assertCount(1, $responseDoc);
        $this->assertEquals('Success', $responseDoc[0]->getStatusCode());
        $this->assertEquals('Success', $responseDoc->offsetGet(0)->getStatusCode());
        $this->assertEquals('Success', $responseDoc->first()->getStatusCode());
    }
}
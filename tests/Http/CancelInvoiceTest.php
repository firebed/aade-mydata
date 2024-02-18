<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class CancelInvoiceTest extends MyDataHttpTestCase
{
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
    }
}
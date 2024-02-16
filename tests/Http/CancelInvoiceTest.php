<?php

namespace Tests\Http;

use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class CancelInvoiceTest extends TestCase
{
    use UsesStubs;
    
    protected function setUp(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
    }
    
    /**
     * @throws GuzzleException
     */
    public function test_it_cancels_invoice()
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
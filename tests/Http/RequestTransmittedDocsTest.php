<?php

namespace Tests\Http;

use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class RequestTransmittedDocsTest extends TestCase
{
    use UsesStubs;

    protected function setUp(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
    }
    
    /**
     * @throws GuzzleException
     */
    public function test_it_returns_transmitted_docs()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-doc-response')),
        ]));

        $request = new RequestTransmittedDocs();
        $requestedDoc = $request->handle();

        $this->assertCount(1, $requestedDoc->getInvoicesDoc());
        $this->assertCount(5, $requestedDoc->getCancelledInvoicesDoc());
        $this->assertCount(3, $requestedDoc->getPaymentMethodsDoc());
    }
}
<?php

namespace Tests\Http;

use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Models\RequestedVatInfo;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class RequestVatInfoTest extends TestCase
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
            new Response(200, body: $this->getStub('request-vat-info-response')),
        ]));

        $request = new RequestVatInfo();
        $requestedVatInfo = $request->handle('01/01/2024', '31/12/2024');

        $this->assertCount(11, $requestedVatInfo->getVatInfo());
    }
}
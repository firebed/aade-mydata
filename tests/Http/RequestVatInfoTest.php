<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestVatInfo;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RequestVatInfoTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
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
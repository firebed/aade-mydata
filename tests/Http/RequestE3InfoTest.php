<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestE3Info;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

/**
 * @version 1.0.10
 */
class RequestE3InfoTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
     * @version 1.0.10
     */
    public function test_vat_info_is_received()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-e3-info-response')),
        ]));

        $request = new RequestE3Info();
        $requestedVatInfo = $request->handle('01/01/2024', '31/12/2024');

        $this->assertCount(10, $requestedVatInfo->getE3Info());

        $vatInfo = $requestedVatInfo->getE3Info()[8];
        $this->assertEquals('999999999', $vatInfo->getVat());
        $this->assertEquals('409365478921457', $vatInfo->getMark());
        $this->assertEquals('2024-02-23T00:00:00', $vatInfo->getIssueDate());
        $this->assertEquals('category1_2', $vatInfo->getClassCategory());
        $this->assertEquals('E3_561_001', $vatInfo->getClassType());
        $this->assertEquals(8.06, $vatInfo->getClassValue());
    }
}
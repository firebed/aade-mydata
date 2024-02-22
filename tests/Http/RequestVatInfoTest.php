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
    public function test_vat_info_is_received()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-vat-info-response')),
        ]));

        $request = new RequestVatInfo();
        $requestedVatInfo = $request->handle('01/01/2024', '31/12/2024');

        $this->assertCount(11, $requestedVatInfo->getVatInfo());
        
        $vatInfo = $requestedVatInfo->getVatInfo()[2];
        $this->assertEquals('461973816871023', $vatInfo->getMark());
        $this->assertFalse($vatInfo->isCancelled());
        $this->assertEquals('2024-01-18T00:00:00', $vatInfo->getIssueDate());
        $this->assertEquals(8.06, $vatInfo->getVat303());
        $this->assertEquals(1.94, $vatInfo->getVat333());
        $this->assertEquals(0.01, $vatInfo->getVat422());
    }
}
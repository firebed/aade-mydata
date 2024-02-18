<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestDocs;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RequestDocsTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
     */
    public function test_docs_are_received()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-doc-response')),
        ]));

        $request = new RequestDocs();
        $requestedDoc = $request->handle();

        $this->assertCount(2, $requestedDoc->getInvoices());
        $this->assertCount(5, $requestedDoc->getCancelledInvoices());
        $this->assertCount(2, $requestedDoc->getIncomeClassifications());
        $this->assertCount(1, $requestedDoc->getExpensesClassifications());
        $this->assertCount(3, $requestedDoc->getPaymentMethods());
        $this->assertCount(3, $requestedDoc->getPaymentMethods());
    }
}
<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\ErrorResponseException;
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
    }

    /**
     * @throws MyDataException
     */
    public function test_error_response_throws_the_errors_of_every_response(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('response-doc-with-errors')),
        ]));

        $request = new RequestDocs();

        try {
            $request->handle();
            $this->fail('An ErrorResponseException was expected.');
        } catch (ErrorResponseException $e) {
            $this->assertCount(4, $e->getErrors());
            $this->assertSame(402, $e->getCode());
            $this->assertStringStartsWith('402: At least one payment method detail must be of POS type', $e->getMessage());
            $this->assertStringContainsString('<ResponseDoc', $request->getResponseXML());
        }
    }
}

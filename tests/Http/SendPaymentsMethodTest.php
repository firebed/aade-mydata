<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Factories\ResponseDocXmlFactory;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;

class SendPaymentsMethodTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
     */
    public function test_it_sends_payments_using_array()
    {
        $paymentMethod1 = PaymentMethod::factory()->make();
        $paymentMethod2 = PaymentMethod::factory()->make();

        $response = new ResponseDocXmlFactory();
        $response->addResponse($response1 = Response::factory()->make());
        $response->addResponse($response2 = Response::factory()->make());

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $response->asXML()),
        ]));

        $sendPayments = new SendPaymentsMethod();
        $responseDoc = $sendPayments->handle([$paymentMethod1, $paymentMethod2]);

        $this->assertCount(2, $responseDoc);
        $this->assertEquals($response1->getInvoiceMark(), $responseDoc[0]->getInvoiceMark());
        $this->assertEquals($response2->getInvoiceMark(), $responseDoc[1]->getInvoiceMark());
    }

    /**
     * @throws MyDataException
     */
    public function test_it_sends_single_payment()
    {
        $paymentMethod = PaymentMethod::factory()->make();

        $response = new ResponseDocXmlFactory();
        $response->addResponse($response1 = Response::factory()->make());

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $response->asXML()),
        ]));

        $sendPayments = new SendPaymentsMethod();
        $responseDoc = $sendPayments->handle($paymentMethod);

        $this->assertCount(1, $responseDoc);
        $this->assertEquals(1, $responseDoc[0]->getIndex());
        $this->assertEquals($response1->getInvoiceMark(), $responseDoc[0]->getInvoiceMark());
    }
}
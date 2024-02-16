<?php

namespace Tests\Http;

use Firebed\AadeMyData\Enums\PaymentMethod as PaymentMethods;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Models\ECRToken;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class SendPaymentsMethodTest extends TestCase
{
    use UsesStubs;

    protected function setUp(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
    }
    
    /**
     * @throws GuzzleException
     */
    public function test_it_sends_payments()
    {
        $ecrToken = new ECRToken();
        $ecrToken->setSigningAuthor('test_author');
        $ecrToken->setSessionNumber('AAA111');

        $pmd = new PaymentMethodDetail();
        $pmd->setType(PaymentMethods::METHOD_7);
        $pmd->setAmount(10);
        $pmd->setTransactionId('test_transaction_id');
        $pmd->setECRToken($ecrToken);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->setInvoiceMark(400008989888809);
        $paymentMethod->addPaymentMethodDetails($pmd);

        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('send-payment-methods-response')),
        ]));

        $sendPayments = new SendPaymentsMethod();
        $responseDoc = $sendPayments->handle([$paymentMethod]);

        $this->assertCount(1, $responseDoc);
        $this->assertEquals(1, $responseDoc[0]->getIndex());
        $this->assertEquals('Success', $responseDoc[0]->getStatusCode());
    }
}
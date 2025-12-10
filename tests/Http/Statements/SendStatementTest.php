<?php

namespace Tests\Http\Statements;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Factories\ResponseDocXmlFactory;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Statements\SendStatement;
use Firebed\AadeMyData\Models\Statements\StatementResponse;
use Firebed\AadeMyData\Models\Statements\Statement;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Tests\Http\MyDataHttpTestCase;

class SendStatementTest extends MyDataHttpTestCase
{
    public function test_dev_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $request = new SendStatement();
        $this->assertEquals('https://mydataapidev.aade.gr/myDataProvider/SendStatement', $request->getUrl());
    }

    public function test_prod_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        $request = new SendStatement();
        $this->assertEquals('https://mydatapi.aade.gr/myDataProvider/SendStatement', $request->getUrl());
    }

    public function test_handle_requires_provider_route()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', false);

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200),
        ]));

        $this->expectException(MyDataException::class);
        $this->expectExceptionMessage('SendStatement can only be used with the Provider route.');

        (new SendStatement())->handle(new Statement());
    }

    /**
     * @throws MyDataException
     */
    public function test_it_sends_statement()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $fakeResponse = new ResponseDocXmlFactory();
        $fakeResponse->addResponse($statementResponse = StatementResponse::factory()->make());

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $fakeResponse->asXML()),
        ]));

        $send = new SendStatement();
        $responseDoc = $send->handle(Statement::factory()->make());

        $this->assertCount(1, $responseDoc);
        $this->assertEquals($statementResponse->getStatementId(), $responseDoc->first()->getStatementId());
        $this->assertEquals($statementResponse->getStatusCode(), $responseDoc->first()->getStatusCode());
    }
}

<?php

namespace Tests\Http\Statements;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Statements\RecallStatement;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Tests\Http\MyDataHttpTestCase;

class RecallStatementTest extends MyDataHttpTestCase
{
    public function test_dev_erp_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
        $request = new RecallStatement();
        $this->assertEquals('https://mydataapidev.aade.gr/RecallStatement', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod');
        $request = new RecallStatement();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/RecallStatement', $request->getUrl());
    }

    public function test_dev_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $request = new RecallStatement();
        $this->assertEquals('https://mydataapidev.aade.gr/myDataProvider/RecallStatement', $request->getUrl());
    }

    public function test_prod_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        $request = new RecallStatement();
        $this->assertEquals('https://mydatapi.aade.gr/myDataProvider/RecallStatement', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_statement_is_recalled()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);

        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('statements/cancel-statement-response')),
        ]));

        $request = new RecallStatement();
        $responseDoc = $request->handle('790', '1234567', 2);

        $this->assertCount(1, $responseDoc);
        $this->assertEquals('790', $responseDoc->first()->getRecallId());
        $this->assertEquals('Success', $responseDoc->first()->getStatusCode());
    }
}

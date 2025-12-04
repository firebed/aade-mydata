<?php

namespace Http\Statements;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Statements\RequestStatements;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Tests\Http\MyDataHttpTestCase;

class RequestStatementsTest extends MyDataHttpTestCase
{
    public function test_dev_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $request = new RequestStatements();
        $this->assertEquals('https://mydataapidev.aade.gr/myDataProvider/RequestStatements', $request->getUrl());
    }

    public function test_prod_provider_url_is_correct()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        $request = new RequestStatements();
        $this->assertEquals('https://mydatapi.aade.gr/myDataProvider/RequestStatements', $request->getUrl());
    }

    public function test_handle_requires_provider_route()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', false);

        MyDataRequest::setHandler(new MockHandler([
            new Response(200)
        ]));

        $this->expectException(MyDataException::class);
        $this->expectExceptionMessage('RequestStatements can only be used with the Provider route.');

        (new RequestStatements())->handle();
    }

    /**
     * @throws MyDataException
     */
    public function test_statements_are_received()
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);

        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('Statements/request-statements-response')),
        ]));

        $request = new RequestStatements();
        $responseDoc = $request->handle();

        $this->assertCount(2, $responseDoc);

        $statement = $responseDoc[1]->getStatement();
        $recallStatement = $responseDoc[1]->getRecallStatement()[0];

        $this->assertEquals('727', $statement->getStatementId());
        $this->assertEquals('2025-11-15T13:07:52.330648', $statement->getSubmissionDateTime());
        $this->assertEquals('110250755', $statement->getEntityVatNumber());
        $this->assertEquals(1, $statement->getLiableUserCategory()->value);
        $this->assertEquals(1, $statement->getProviderType()->value);
        $this->assertTrue($statement->getIsB2BTransactions());
        $this->assertTrue($statement->getIsB2CTransactions());
        $this->assertFalse($statement->getIsB2GTransactions());
        $this->assertEquals('110286266', $statement->getProviderVatNumber());
        $this->assertEquals('2020_11_106Cloud Services _001_Oxygen-Pelatologio_V1_18112020', $statement->getProviderLicenceNumber());
        $this->assertEquals('111041', $statement->getProviderContractNumber());
        $this->assertEquals('2025-07-26T11:16:10', $statement->getProviderContractConclusionDate());
        $this->assertEquals('2025-07-27T11:16:10', $statement->getProviderContractActivationDate());
        $this->assertEquals('2025-07-27', $statement->getIssueStartDate());
        $this->assertEquals('VODAFONE', $statement->getInternetProvider());
        $this->assertEquals('RANDOMNUMBER', $statement->getInternetProviderContractNumber());
        $this->assertEquals('2007-04-12T18:27:03', $statement->getInternetProviderContractDate());

        $this->assertEquals(727, $recallStatement->getStatementID());
        $this->assertEquals(728, $recallStatement->getRecallId());
        $this->assertEquals(2, $recallStatement->getRecallStatus()->value);
        $this->assertEquals('2025-11-15', $recallStatement->getTransactionDate());
        $this->assertEquals('110286266', $recallStatement->getRecallVatNumber());
    }
}

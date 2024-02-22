<?php

namespace Tests\Http;

use Firebed\AadeMyData\Enums\InvoiceDetailType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestMyIncome;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RequestMyIncomeTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
     */
    public function test_my_income_is_received()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-my-income-response')),
        ]));

        $request = new RequestMyIncome();
        $bookInfo = $request->handle('01/01/2024', '31/12/2024');
        
        $this->assertNotNull($bookInfo->getContinuationToken());
        $this->assertEquals('78A4SD5FG5GH55W5DFV5HJN5', $bookInfo->getContinuationToken()->getNextPartitionKey());
        $this->assertEquals('AS25AS45F45GD55', $bookInfo->getContinuationToken()->getNextRowKey());
        
        $this->assertCount(11, $bookInfo->getBookInfo());
        
        // Test first book info
        $book = $bookInfo->getBookInfo()[0];
        $this->assertCount(17, $book->attributes());
        $this->assertEquals('429630274', $book->getCounterVatNumber());
        $this->assertEquals('2022-01-09', $book->getIssueDate());
        $this->assertTrue($book->getSelfPricing());
        $this->assertEquals(InvoiceDetailType::TYPE_1, $book->getInvoiceDetailType());
        $this->assertEquals(InvoiceType::TYPE_2_1, $book->getInvType());
        $this->assertEquals(4302.83, $book->getNetValue());
        $this->assertEquals(0, $book->getVatAmount());
        $this->assertEquals(860.57, $book->getWithheldAmount());
        $this->assertEquals(0, $book->getOtherTaxesAmount());
        $this->assertEquals(0, $book->getStampDutyAmount());
        $this->assertEquals(0, $book->getFeesAmount());
        $this->assertEquals(0, $book->getDeductionsAmount());
        $this->assertEquals(0, $book->getThirdPartyAmount());
        $this->assertEquals(3442.26, $book->getGrossValue());
        $this->assertEquals(1, $book->getCount());
        $this->assertEquals(471097872971935, $book->getMinMark());
        $this->assertEquals(471097872971935, $book->getMaxMark());
    }
}
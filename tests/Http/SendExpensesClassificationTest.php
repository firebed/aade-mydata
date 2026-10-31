<?php

namespace Tests\Http;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Factories\ResponseDocXmlFactory;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendExpensesClassification;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoicesExpensesClassificationDetail;
use Firebed\AadeMyData\Models\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;

class SendExpensesClassificationTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
     */
    public function test_it_sends_expenses_classification()
    {
        $invoiceMark = 900001843640886;
        
        $classification = new InvoiceExpensesClassification();
        $classification->setInvoiceMark($invoiceMark);
        $classification->setInvoicesExpensesClassificationDetails([
            new InvoicesExpensesClassificationDetail([
                'lineNumber' => 1,
                'expensesClassificationDetailData' => [
                    new ExpensesClassification([
                        'classificationType' => ExpenseClassificationType::E3_102_001,
                        'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                        'amount' => 10
                    ]),
                    new ExpensesClassification([
                        'classificationType' => ExpenseClassificationType::VAT_361,
                        'amount' => 10
                    ])
                ],
            ]),
        ]);

        $response = new ResponseDocXmlFactory();
        $response->addResponse(Response::factory()->make([
            'invoiceMark' => $invoiceMark,
        ]));

        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $response->asXML()),
        ]));

        $sender = new SendExpensesClassification();
        $responseDoc = $sender->handle([$classification]);

        $this->assertCount(1, $responseDoc);
        $this->assertEquals($invoiceMark, $responseDoc->first()->getInvoiceMark());
        $this->assertNotNull($responseDoc->first()->getClassificationMark());

        $this->assertSame('ExpensesClassificationsDoc', $sender->getRequestDom()->documentElement->localName);
        $this->assertStringContainsString('<ExpensesClassificationsDoc', $sender->getRequestXml());
        $this->assertNotNull($sender->getResponseDom());
        $this->assertSame('ResponseDoc', $sender->getResponseDom()->documentElement->localName);
    }

    /**
     * @throws MyDataException
     */
    public function test_it_sends_post_per_invoice_query_parameter()
    {
        $response = new ResponseDocXmlFactory();
        $response->addResponse(Response::factory()->make());

        $handler = new MockHandler([
            new HttpResponse(200, body: $response->asXML()),
            new HttpResponse(200, body: $response->asXML()),
        ]);
        MyDataRequest::setHandler($handler);

        $classification = (new InvoiceExpensesClassification())->setInvoiceMark(900001843640886);

        (new SendExpensesClassification())->handle([$classification], postPerInvoice: true);
        $this->assertSame('postPerInvoice=true', $handler->getLastRequest()->getUri()->getQuery());

        (new SendExpensesClassification())->handle([$classification]);
        $this->assertSame('', $handler->getLastRequest()->getUri()->getQuery());
    }
}
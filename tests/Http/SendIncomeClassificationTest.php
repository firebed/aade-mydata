<?php

namespace Tests\Http;

use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Factories\ResponseDocXmlFactory;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendIncomeClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesIncomeClassificationDetail;
use Firebed\AadeMyData\Models\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;

class SendIncomeClassificationTest extends MyDataHttpTestCase
{
    /**
     * @throws MyDataException
     */
    public function test_it_sends_income_classification()
    {
        $invoiceMark = 900001843640886;

        $classification = new InvoiceIncomeClassification();
        $classification->setInvoiceMark($invoiceMark);
        $classification->setInvoicesIncomeClassificationDetails([
            new InvoicesIncomeClassificationDetail([
                'lineNumber' => 1,
                'incomeClassificationDetailData' => [
                    new IncomeClassification([
                        'classificationType' => IncomeClassificationType::E3_561_001,
                        'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_2,
                        'amount' => 10
                    ]),
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

        $sender = new SendIncomeClassification();
        $responseDoc = $sender->handle([$classification]);

        $this->assertCount(1, $responseDoc);
        $this->assertEquals($invoiceMark, $responseDoc->first()->getInvoiceMark());
        $this->assertNotNull($responseDoc->first()->getClassificationMark());
    }
}
<?php

use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Models\Enums\IncomeClassificationCode;
use Firebed\AadeMyData\Models\Enums\InvoiceType;
use Firebed\AadeMyData\Models\Enums\PaymentMethods;
use Firebed\AadeMyData\Models\Enums\VatType;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\TestInvoice;

require __DIR__ . '/vendor/autoload.php';

MyDataRequest::setEnvironment('dev');
MyDataRequest::setCredentials('okangr', 'f4b290964c7b4cb699cf79c4c3ea6cc3');

echo "1. RequestDocs\n";
echo "2. RequestSubmittedDocs\n";
echo "3. CancelInvoice\n";
echo "4. SendInvoices\n";
echo "0. Exit\n";

$action = rtrim(fgets(STDIN));

while ($action !== "0") {

    switch ($action) {
        case "1":
        {
            $response = (new RequestDocs())->handle("");
            dump($response);
            break;
        }

        case "2":
        {
            $response = (new RequestTransmittedDocs())->handle("");
            dump($response);
            break;
        }

        case "3":
        {
            echo "Enter MARK: ";
            $mark = rtrim(fgets(STDIN));

            $response = (new CancelInvoice())->handle($mark);
            dump($response);
            break;
        }

        case "4":
        {
            $invoice = new TestInvoice();
            $invoice->setHeaders('A', 1, date('Y-m-d'), InvoiceType::TYPE_1_1);
            $invoice->addPaymentMethodType(PaymentMethods::METHOD_5, 62);
            $invoice->addInvoiceRowType(1, 50, VatType::VAT_1, 12, IncomeClassificationCode::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 50);
            $invoice->setInvoiceSummaryType(50, 12, 62, IncomeClassificationCode::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 50);
            $invoicesDoc = new InvoicesDoc();
            $invoicesDoc->addInvoice($invoice);
            $response = (new SendInvoices())->handle($invoicesDoc);
            dump($response);
        }

        default:
            exit;
    }

    $action = rtrim(fgets(STDIN));
}

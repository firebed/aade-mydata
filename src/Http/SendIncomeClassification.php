<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\IncomeClassificationsDoc;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\IncomeClassificationsDocWriter;
use Firebed\AadeMyData\Xml\ResponseDocReader;

class SendIncomeClassification extends MyDataRequest
{
    use HasResponseDom;
    use HasRequestDom;

    /**
     * <ul>
     * <li>Το πεδίο transactionMode όταν παίρνει την τιμή 1 υποδηλώνει απόρριψη του
     * παραστατικού λόγω διαφωνίας, όταν παίρνει την τιμή 2 σημαίνει απόκλιση στα ποσά</li>
     * <li>Ο χρήστης μπορεί να συμπεριλάβει είτε το στοιχείο transactionMode ή λίστα
     * στοιχείων invoicesIncomeClassificationDetails</li>
     * <li>Κάθε στοιχείο invoicesIncomeClassificationDetails περιέχει ένα lineNumber και μια
     * λίστα στοιχείων invoiceIncomeClassificationDetailData</li>
     * <li>Το πεδίο lineNumber αναφέρεται στον αντίστοιχο αριθμό γραμμής του αρχικού
     * παραστατικού με Μοναδικός Αριθμός Καταχώρησης αυτό του πεδίου mark</li>
     * <li>Για την περίπτωση εκείνη και μόνο που η μέθοδος κληθεί από τρίτο πρόσωπο
     * (όπως εκπρόσωπος Ν.Π. ή λογιστής), ο ΑΦΜ της οντότητας που αναφέρεται ο
     * χαρακτηρισμός του παραστατικού αποστέλλεται μέσω του πεδίου
     * entityVatNumber, διαφορετικά το εν λόγω πεδίο παραμένει κενό</li>
     * </ul>
     *
     * @param  IncomeClassificationsDoc|InvoiceIncomeClassification|array  $incomeClassifications
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function handle(IncomeClassificationsDoc|InvoiceIncomeClassification|array $incomeClassifications, bool $postPerInvoice = false): ResponseDoc
    {
        if (!$incomeClassifications instanceof IncomeClassificationsDoc) {
            $incomeClassifications = new IncomeClassificationsDoc($incomeClassifications);
        }

        $query = [];
        if ($postPerInvoice) {
            $query['postPerInvoice'] = 'true';
        }

        $writer = new IncomeClassificationsDocWriter();
        $requestXml = $writer->asXML($incomeClassifications);
        $this->requestDom = $writer->getDomDocument();

        $responseXml = $this->post($query, $requestXml);

        $reader = new ResponseDocReader();
        $responseDoc = $reader->parseXML($responseXml);
        $this->requestDom = $reader->getDomDocument();

        return $responseDoc;
    }
}
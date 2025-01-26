<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\ExpensesClassificationsDoc;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\ExpensesClassificationsDocWriter;
use Firebed\AadeMyData\Xml\ResponseDocReader;

class SendExpensesClassification extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;
    
    /**
     * <ul>
     * <li>Το πεδίο transactionMode όταν παίρνει την τιμή 1 υποδηλώνει απόρριψη του
     * παραστατικού λόγω διαφωνίας, όταν παίρνει την τιμή 2 σημαίνει απόκλιση στα
     * ποσά</li>
     * <li>Ο χρήστης μπορεί να συμπεριλάβει είτε το στοιχείο transactionMode ή λίστα
     * στοιχείων invoicesExpensesClassificationDetails</li>
     * <li>Κάθε στοιχείο invoicesExpensesClassificationDetails περιέχει ένα lineNumber και
     * μια λίστα στοιχείων expensesClassificationDetailData</li>
     * <li>Το πεδίο lineNumber αναφέρεται στον αντίστοιχο αριθμό γραμμής του αρχικού
     * παραστατικού με Μοναδικός Αριθμός Καταχώρησης αυτό του πεδίου mark</li>
     * <li>Για την περίπτωση εκείνη και μόνο που η μέθοδος κληθεί από τρίτο πρόσωπο
     * (όπως εκπρόσωπος Ν.Π. ή λογιστής), ο ΑΦΜ της οντότητας που αναφέρεται ο
     * χαρακτηρισμός του παραστατικού αποστέλλεται μέσω του πεδίου
     * entityVatNumber, διαφορετικά το εν λόγω πεδίο παραμένει κενό</li>
     * <li>Όταν η παράμετρος postPerInvoice καλείται με τιμή true, τότε αυτό σημαίνει ότι οι
     * χαρακτηρισμοί εξόδων υποβάλλονται σε επίπεδο παραστατικού και όχι ανά
     * γραμμή</li>
     * </ul>
     * With this method the user can classify invoices that produce income.
     *
     * @param ExpensesClassificationsDoc|InvoiceExpensesClassification[] $expensesClassifications
     * @throws MyDataException
     */
    public function handle(ExpensesClassificationsDoc|array $expensesClassifications, bool $postPerInvoice = false): ResponseDoc
    {
        if (!$expensesClassifications instanceof ExpensesClassificationsDoc) {
            $expensesClassifications = new ExpensesClassificationsDoc($expensesClassifications);
        }
        
        $query = [];
        if ($postPerInvoice) {
            $query['postPerInvoice'] = 'true';
        }
        
        $writer = new ExpensesClassificationsDocWriter();
        $requestXml = $writer->asXML($expensesClassifications);
        $this->requestDom = $writer->getDomDocument();

        $responseXml = $this->post($query, $requestXml);
        
        $reader  = new ResponseDocReader();
        $responseDoc = $reader->parseXML($responseXml);
        $this->requestDom = $reader->getDomDocument();
        
        return $responseDoc;
    }
}

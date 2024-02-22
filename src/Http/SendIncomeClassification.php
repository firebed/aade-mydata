<?php

namespace Firebed\AadeMyData\Http;

use Exception;
use Firebed\AadeMyData\Enums\TransactionMode;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\InvoicesIncomeClassificationDetail;
use Firebed\AadeMyData\Models\ResponseDoc;

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
     * @param InvoicesIncomeClassificationDetail|InvoicesIncomeClassificationDetail[] $classificationDetails
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function handle(InvoicesIncomeClassificationDetail|array $classificationDetails): ResponseDoc
    {
        //TODO Implement
        throw new MyDataException('Not implemented');
    }
}
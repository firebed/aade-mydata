<?php

namespace Firebed\AadeMyData\Models;

class InvoiceIncomeClassification extends Type
{
    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getInvoiceMark(): ?string
    {
        return $this->get('invoiceMark');
    }

    /**
     * @param string $invoiceMark Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function setInvoiceMark(string $invoiceMark): void
    {
        $this->set('invoiceMark', $invoiceMark);
    }

    /**
     * Συμπληρώνεται από την υπηρεσία.
     *
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Χαρακτηρισμού
     */
    public function getClassificationMark(): ?string
    {
        return $this->get('classificationMark');
    }

    /**
     * @return string|null ΑΦΜ Οντότητας Αναφοράς
     */
    public function getEntityVatNumber(): ?string
    {
        return $this->get('entityVatNumber');
    }

    /**
     * Για την περίπτωση εκείνη και μόνο που η μέθοδος κληθεί από τρίτο πρόσωπο(όπως εκπρόσωπος Ν.Π. ή λογιστής),
     * ο ΑΦΜ της οντότητας που αναφέρεται ο χαρακτηρισμός του παραστατικού αποστέλλεται μέσω του πεδίου entityVatNumber,
     * διαφορετικά το εν λόγω πεδίο παραμένει κενό.
     * 
     * @param string $entityVatNumber ΑΦΜ Οντότητας Αναφοράς
     */
    public function setEntityVatNumber(string $entityVatNumber): void
    {
        $this->set('entityVatNumber', $entityVatNumber);
    }

    /**
     * @return int|null Είδος Συναλλαγής
     */
    public function getTransactionMode(): ?int
    {
        return $this->get('transactionMode');
    }

    /**
     * <ol>
     * <li>Reject (απόρριψη του παραστατικού λόγω διαφωνίας)</li>
     * <li>Deviation (απόκλιση στα ποσά)</li>
     * </ol>
     * 
     * Ο χρήστης μπορεί να συμπεριλάβει είτε το στοιχείο transactionMode ή λίστα στοιχείων invoicesIncomeClassificationDetails.
     * 
     * @param int $transactionMode Είδος Συναλλαγής
     */
    public function setTransactionMode(int $transactionMode): void
    {
        $this->set('transactionMode', $transactionMode);
    }

    /**
     * @return int|null Αριθμός Γραμμής
     */
    public function getLineNumber(): ?int
    {
        return $this->get('lineNumber');
    }

    /**
     * Αναφέρεται στον αντίστοιχο αριθμό γραμμής του αρχικού παραστατικού με Μοναδικός Αριθμός Καταχώρησης αυτό του πεδίου mark.
     * 
     * @param int $lineNumber Αριθμός Γραμμής
     */
    public function setLineNumber(int $lineNumber): void
    {
        $this->set('lineNumber', $lineNumber);
    }

    /**
     * @return IncomeClassification|null
     */
    public function getIncomeClassificationDetailData(): ?IncomeClassification
    {
        return $this->get('incomeClassificationDetailData');
    }

    /**
     * Κάθε στοιχείο invoicesIncomeClassificationDetails περιέχει ένα lineNumber και
     * μια λίστα στοιχείων invoiceIncomeClassificationDetailData.
     * 
     * @param IncomeClassification $incomeClassificationDetailData
     */
    public function setIncomeClassificationDetailData(IncomeClassification $incomeClassificationDetailData): void
    {
        $this->set('incomeClassificationDetailData', $incomeClassificationDetailData);
    }

}
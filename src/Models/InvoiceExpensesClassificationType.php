<?php

namespace Firebed\AadeMyData\Models;

class InvoiceExpensesClassificationType extends Type
{
    /**
     * @return string Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getInvoiceMark(): string
    {
        return $this->get('invoiceMark');
    }

    /**
     * <h2>Μοναδικός Αριθμός Καταχώρησης Παραστατικού</h2>
     *
     * @param string $invoiceMark
     * @return $this
     */
    public function setInvoiceMark(string $invoiceMark): self
    {
        return $this->put('invoiceMark', $invoiceMark);
    }

    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Χαρακτηρισμού
     */
    public function getClassificationMark(): ?string
    {
        return $this->get('classificationMark');
    }

    /**
     * <h2>Μοναδικός Αριθμός Καταχώρησης Χαρακτηρισμού</h2>
     *
     * <p>Συμπληρώνεται από την υπηρεσία</p>
     *
     * @param string $classificationMark
     * @return $this
     */
    public function setClassificationMark(string $classificationMark): self
    {
        return $this->put('classificationMark', $classificationMark);
    }

    /**
     * @return string|null ΑΦΜ Οντότητας Αναφοράς
     */
    public function getEntityVatNumber(): ?string
    {
        return $this->get('entityVatNumber');
    }

    /**
     * <h2>ΑΦΜ Οντότητας Αναφοράς</h2>
     *
     * <p>Για την περίπτωση εκείνη και μόνο που η μέθοδος κληθεί από τρίτο πρόσωπο
     * (όπως εκπρόσωπος Ν.Π. ή λογιστής), ο ΑΦΜ της οντότητας που αναφέρεται ο
     * χαρακτηρισμός του παραστατικού αποστέλλεται μέσω του πεδίου
     * entityVatNumber, διαφορετικά το εν λόγω πεδίο παραμένει κενό.</p>
     *
     * @param string $entityVatNumber
     * @return $this
     */
    public function setEntityVatNumber(string $entityVatNumber): self
    {
        return $this->put('entityVatNumber', $entityVatNumber);
    }

    /**
     * @return int Είδος Συναλλαγής
     */
    public function getTransactionMode(): int
    {
        return $this->get('transactionMode');
    }

    /**
     * <h2>Είδος Συναλλαγή</h2>
     *
     * <p>Το πεδίο transactionMode όταν παίρνει την τιμή 1 υποδηλώνει απόρριψη του
     * παραστατικού.</p>
     *
     * @param int $transactionMode Αποδεκτές τιμές: 1 = Reject
     * @return $this
     */
    public function setTransactionMode(int $transactionMode): self
    {
        return $this->put('transactionMode', $transactionMode);
    }

    /**
     * @return int Αριθμός Γραμμής
     */
    public function getLineNumber(): int
    {
        return $this->get('lineNumber');
    }

    /**
     * <h2>Αριθμός Γραμμής</h2>
     *
     * <p>Το πεδίο lineNumber αναφέρεται στον αντίστοιχο αριθμό γραμμής του αρχικού
     * παραστατικού με Μοναδικός Αριθμός Καταχώρησης αυτό του πεδίου mark.</p>
     *
     * @param $lineNumber
     * @return $this
     */
    public function setLineNumber($lineNumber): self
    {
        return $this->put('lineNumber', $lineNumber);
    }

    /**
     * @return ExpensesClassificationType
     */
    public function getExpensesClassificationDetailData(): ExpensesClassificationType
    {
        return $this->get('expensesClassificationDetailData');
    }

    /**
     * @param ExpensesClassificationType $incomeClassificationType
     * @return $this
     */
    public function setExpensesClassificationDetailData(ExpensesClassificationType $incomeClassificationType): self
    {
        return $this->put('expensesClassificationDetailData', $incomeClassificationType);
    }
}
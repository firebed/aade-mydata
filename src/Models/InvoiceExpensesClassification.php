<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\TransactionMode;
use Firebed\AadeMyData\Xml\ExpensesClassificationsDocWriter;

class InvoiceExpensesClassification extends Type
{
    protected array $expectedOrder = [
        'invoiceMark',
        'entityVatNumber',
        'transactionMode',
        'invoicesExpensesClassificationDetails',
        'classificationPostMode',
    ];

    protected array $casts = [
        'transactionMode' => TransactionMode::class,
        'invoicesExpensesClassificationDetails' => InvoicesExpensesClassificationDetail::class,
    ];

    /**
     * @return int|null Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getInvoiceMark(): ?int
    {
        return $this->get('invoiceMark');
    }

    /**
     * @param  int  $invoiceMark  Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function setInvoiceMark(int $invoiceMark): static
    {
        return $this->set('invoiceMark', $invoiceMark);
    }

    /**
     * Συμπληρώνεται από την υπηρεσία.
     *
     * @return int|null Μοναδικός Αριθμός Καταχώρησης Χαρακτηρισμού
     */
    public function getClassificationMark(): ?int
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
     * Για την περίπτωση εκείνη και μόνο που η μέθοδος κληθεί από τρίτο πρόσωπο (όπως εκπρόσωπος Ν.Π. ή λογιστής),
     * ο ΑΦΜ της οντότητας που αναφέρεται ο χαρακτηρισμός του παραστατικού αποστέλλεται μέσω του πεδίου entityVatNumber,
     * διαφορετικά το εν λόγω πεδίο παραμένει κενό.
     *
     * @param  string|null  $entityVatNumber  ΑΦΜ Οντότητας Αναφοράς
     */
    public function setEntityVatNumber(?string $entityVatNumber): static
    {
        return $this->set('entityVatNumber', $entityVatNumber);
    }

    /**
     * @return TransactionMode|null Είδος Συναλλαγής
     */
    public function getTransactionMode(): ?TransactionMode
    {
        return $this->get('transactionMode');
    }

    /**
     * <ol>
     * <li>Reject (απόρριψη του παραστατικού λόγω διαφωνίας)</li>
     * <li>Deviation (απόκλιση στα ποσά)</li>
     * </ol>
     *
     * Ο χρήστης μπορεί να συμπεριλάβει είτε το στοιχείο transactionMode ή λίστα στοιχείων invoicesExpensesClassificationDetails.
     *
     * @param  TransactionMode|int|null  $transactionMode  Είδος Συναλλαγής
     */
    public function setTransactionMode(TransactionMode|int|null $transactionMode): static
    {
        return $this->set('transactionMode', $transactionMode);
    }

    /**
     * @return InvoicesExpensesClassificationDetail[]|null Λίστα Χαρακτηρισμών Εξόδων
     */
    public function getInvoicesExpensesClassificationDetails(): ?array
    {
        return $this->get('invoicesExpensesClassificationDetails');
    }

    public function addInvoicesExpensesClassificationDetails(InvoicesExpensesClassificationDetail $expensesClassification): static
    {
        return $this->push('invoicesExpensesClassificationDetails', $expensesClassification);
    }
    
    /**
     * Κάθε στοιχείο invoicesExpensesClassificationDetails περιέχει ένα lineNumber και
     * μια λίστα στοιχείων expensesClassificationDetailData.
     *
     * @param  InvoicesExpensesClassificationDetail[]|null  $invoicesExpensesClassificationDetails  Λίστα Χαρακτηρισμών Εξόδων
     */
    public function setInvoicesExpensesClassificationDetails(?array $invoicesExpensesClassificationDetails): static
    {
        return $this->set('invoicesExpensesClassificationDetails', $invoicesExpensesClassificationDetails);
    }

    /**
     * Περιγράφει με ποιον τρόπο έχει υποβληθεί ο χαρακτηρισμός:
     *
     * – `0` παλιός τρόπος (ανά γραμμή),
     * - `1` νέος τρόπος (ανά παραστατικό).
     *
     * @return int|null Μέθοδος Υποβολής Χαρακτηρισμού
     */
    public function getClassificationPostMode(): ?int
    {
        return $this->get('classificationPostMode');
    }

    public function toXml(bool $asDoc = false): string
    {
        $writer = new ExpensesClassificationsDocWriter();
        $fullXml = $writer->asXML(new ExpensesClassificationsDoc($this));

        if ($asDoc) {
            return $fullXml;
        }

        $doc = $writer->getDomDocument();
        return $doc->saveXML($doc->getElementsByTagName('expensesInvoiceClassification')->item(0));
    }
}
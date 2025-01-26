<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\TransactionMode;
use Firebed\AadeMyData\Xml\IncomeClassificationsDocWriter;

class InvoiceIncomeClassification extends Type
{
    protected array $expectedOrder = [
        'invoiceMark',
        'entityVatNumber',
        'transactionMode',
        'invoicesIncomeClassificationDetails',
        'classificationPostMode',
    ];

    protected array $casts = [
        'transactionMode' => TransactionMode::class,
        'invoicesIncomeClassificationDetails' => InvoicesIncomeClassificationDetail::class,
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
     * Ο χρήστης μπορεί να συμπεριλάβει είτε το στοιχείο transactionMode ή λίστα στοιχείων invoicesIncomeClassificationDetails.
     *
     * @param  TransactionMode|int|null  $transactionMode  Είδος Συναλλαγής
     */
    public function setTransactionMode(TransactionMode|int|null $transactionMode): static
    {
        return $this->set('transactionMode', $transactionMode);
    }

    /**
     * @return InvoicesIncomeClassificationDetail[]|null Στοιχεία Χαρακτηρισμού Εσόδων
     */
    public function getInvoicesIncomeClassificationDetails(): ?array
    {
        return $this->get('invoicesIncomeClassificationDetails');
    }

    public function addInvoicesIncomeClassificationDetails(InvoicesIncomeClassificationDetail $incomeClassification): static
    {
        return $this->push('invoicesIncomeClassificationDetails', $incomeClassification);
    }

    /**
     * Κάθε στοιχείο invoicesIncomeClassificationDetails περιέχει ένα lineNumber και
     * μια λίστα στοιχείων incomeClassificationDetailData.
     *
     * @param  InvoicesIncomeClassificationDetail[]|null  $incomeClassifications  Στοιχεία Χαρακτηρισμού Εσόδων
     */
    public function setInvoicesIncomeClassificationDetails(?array $incomeClassifications): static
    {
        return $this->set('invoicesIncomeClassificationDetails', $incomeClassifications);
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
        $writer = new IncomeClassificationsDocWriter();
        $fullXml = $writer->asXML(new IncomeClassificationsDoc($this));

        if ($asDoc) {
            return $fullXml;
        }

        $doc = $writer->getDomDocument();
        return $doc->saveXML($doc->getElementsByTagName('incomeInvoiceClassification')->item(0));
    }
}
<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\TransactionMode;

class InvoiceExpensesClassification extends Type
{
    protected array $casts = [
        'transactionMode'                       => TransactionMode::class,
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
     * @param int $invoiceMark Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function setInvoiceMark(int $invoiceMark): void
    {
        $this->set('invoiceMark', $invoiceMark);
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
     * διαφορετικά το εν λόγω πεδίο παραμένει κενό
     *
     * @param string $entityVatNumber ΑΦΜ Οντότητας Αναφοράς
     */
    public function setEntityVatNumber(string $entityVatNumber): void
    {
        $this->set('entityVatNumber', $entityVatNumber);
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
     * Ο χρήστης μπορεί να συμπεριλάβει είτε το στοιχείο transactionMode ή λίστα στοιχείων invoicesExpensesClassificationDetails<
     *
     * @param TransactionMode|int $transactionMode Είδος Συναλλαγής
     */
    public function setTransactionMode(TransactionMode|int $transactionMode): void
    {
        $this->set('transactionMode', $transactionMode);
    }

    /**
     * @return InvoicesExpensesClassificationDetail[]|null Λίστα Χαρακτηρισμών Εξόδων
     */
    public function getInvoicesExpensesClassificationDetails(): ?array
    {
        return $this->get('invoicesExpensesClassificationDetails');
    }

    /**
     * Κάθε στοιχείο invoicesExpensesClassificationDetails περιέχει ένα lineNumber και
     * μια λίστα στοιχείων expensesClassificationDetailData.
     *
     * @param InvoicesExpensesClassificationDetail[] $invoicesExpensesClassificationDetails Λίστα Χαρακτηρισμών Εξόδων
     */
    public function setInvoicesExpensesClassificationDetails(array $invoicesExpensesClassificationDetails): void
    {
        $this->set('invoicesExpensesClassificationDetails', $invoicesExpensesClassificationDetails);
    }

    /**
     * Όταν η παράμετρος postPerInvoice καλείται με τιμή true, τότε αυτό σημαίνει ότι οι
     * χαρακτηρισμοί εξόδων υποβάλλονται σε επίπεδο παραστατικού και όχι ανά
     * γραμμή. Περισσότερες πληροφορίες στον σύνδεσμο:
     *
     * @return int|null Μέθοδος Υποβολής Χαρακτηρισμού
     */
    public function getClassificationPostMode(): ?int
    {
        return $this->get('classificationPostMode');
    }

    /**
     * Όταν η παράμετρος postPerInvoice καλείται με τιμή true, τότε αυτό σημαίνει ότι οι
     * χαρακτηρισμοί εξόδων υποβάλλονται σε επίπεδο παραστατικού και όχι ανά
     * γραμμή. Περισσότερες πληροφορίες στον σύνδεσμο:
     *
     * @param int $classificationPostMode Μέθοδος Υποβολής Χαρακτηρισμού
     */
    public function setClassificationPostMode(int $classificationPostMode): void
    {
        $this->set('classificationPostMode', $classificationPostMode);
    }
}
<?php

namespace Firebed\AadeMyData\Models;


/**
 * <p>Στις περιπτώσεις που ο χρήστης καλέσει μια εκ των δυο μεθόδων λήψης δεδομένων
 * (RequestDocs, RequestTransmittedDocs), θα λάβει ένα αντικείμενο RequestedDoc
 * σε xml μορφή. Το αντικείμενο θα περιλαμβάνει λίστες παραστατικών, χαρακτηρισμών
 * εσόδων – εξόδων και ακυρώσεων παραστατικών οι οποίες έχουν mark μεγαλύτερο
 * από αυτό που εισήχθη ως παράμετρο, καθώς και το στοιχείο continuationToken,
 * σε περίπτωση που ο όγκος των δεδομένων υπερβαίνει το επιτρεπτό όριο και η
 * λήψη τους γίνει τμηματικά.</p>
 *
 * <p>Σε περίπτωση που θα επιστρέφεται το στοιχείο continuationToken τα πεδία
 * nextPartitionKey και nextRowKey θα είναι συμπληρωμένα από την υπηρεσία και
 * χρησιμοποιούνται στην επόμενη κλήση της ίδιας μεθόδου που είχε καλεστεί από
 * τον χρήστη.</p>
 */
class RequestedDoc extends Type
{
    /**
     * @return ContinuationToken|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function getContinuationToken(): ?ContinuationToken
    {
        return $this->get('continuationToken');
    }

    /**
     * @param ContinuationToken $continuationToken Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function setContinuationToken(ContinuationToken $continuationToken): void
    {
        $this->put('continuationToken', $continuationToken);
    }

    /**
     * @return InvoicesDoc|null Λίστα Παραστατικών
     */
    public function getInvoicesDoc(): ?InvoicesDoc
    {
        return $this->get('invoicesDoc');
    }

    /**
     * @param InvoicesDoc $invoicesDoc Λίστα Παραστατικών
     */
    public function setInvoicesDoc(InvoicesDoc $invoicesDoc): void
    {
        $this->put('invoicesDoc', $invoicesDoc);
    }

    /**
     * @return CancelledInvoice[]|null Λίστα ακυρώσεων
     */
    public function getCancelledInvoicesDoc(): ?array
    {
        return $this->get('cancelledInvoicesDoc');
    }

    /**
     * @param CancelledInvoice[] $cancelledInvoicesDoc Λίστα ακυρώσεων
     */
    public function setCancelledInvoicesDoc(array $cancelledInvoicesDoc): void
    {
        $this->put('cancelledInvoicesDoc', $cancelledInvoicesDoc);
    }

    /**
     * @return InvoiceIncomeClassification|null Λίστα Χαρακτηρισμών Εσόδων
     */
    public function getIncomeClassificationsDoc(): ?InvoiceIncomeClassification
    {
        return $this->get('incomeClassificationsDoc');
    }

    /**
     * @param InvoiceIncomeClassification $incomeClassificationsDoc Λίστα Χαρακτηρισμών Εσόδων
     */
    public function setIncomeClassificationsDoc(InvoiceIncomeClassification $incomeClassificationsDoc): void
    {
        $this->put('incomeClassificationsDoc', $incomeClassificationsDoc);
    }

    /**
     * @return InvoiceExpensesClassification|null Λίστα Χαρακτηρισμών Εξόδων
     */
    public function getExpensesClassificationsDoc(): ?InvoiceExpensesClassification
    {
        return $this->get('expensesClassificationsDoc');
    }

    /**
     * @param InvoiceExpensesClassification $expensesClassificationsDoc Λίστα Χαρακτηρισμών Εξόδων
     */
    public function setExpensesClassificationsDoc(InvoiceExpensesClassification $expensesClassificationsDoc): void
    {
        $this->put('expensesClassificationsDoc', $expensesClassificationsDoc);
    }
}
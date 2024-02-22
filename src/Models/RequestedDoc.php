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
    protected array $casts = [
        'continuationToken'          => ContinuationToken::class,
        'invoicesDoc'                => InvoicesDoc::class,
        'cancelledInvoicesDoc'       => CancelledInvoicesDoc::class,
        'incomeClassificationsDoc'   => IncomeClassificationsDoc::class,
        'expensesClassificationsDoc' => ExpensesClassificationsDoc::class,
        'paymentMethodsDoc'          => PaymentMethodsDoc::class,
    ];

    /**
     * @return ContinuationToken|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function getContinuationToken(): ?ContinuationToken
    {
        return $this->get('continuationToken');
    }

    /**
     * @return InvoicesDoc|null Λίστα Παραστατικών
     */
    public function getInvoices(): ?InvoicesDoc
    {
        return $this->get('invoicesDoc');
    }

    /**
     * @return CancelledInvoicesDoc|null Λίστα ακυρώσεων
     */
    public function getCancelledInvoices(): ?CancelledInvoicesDoc
    {
        return $this->get('cancelledInvoicesDoc');
    }

    /**
     * @return IncomeClassificationsDoc|null Λίστα Χαρακτηρισμών Εσόδων
     */
    public function getIncomeClassifications(): ?IncomeClassificationsDoc
    {
        return $this->get('incomeClassificationsDoc');
    }

    /**
     * @return ExpensesClassificationsDoc|null Λίστα Χαρακτηρισμών Εξόδων
     */
    public function getExpensesClassifications(): ?ExpensesClassificationsDoc
    {
        return $this->get('expensesClassificationsDoc');
    }

    /**
     * @return PaymentMethodsDoc|null Λίστα Τρόπων Πληρωμής
     * @version 1.0.8
     */
    public function getPaymentMethods(): ?PaymentMethodsDoc
    {
        return $this->get('paymentMethodsDoc');
    }
}
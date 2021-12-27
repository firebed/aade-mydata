<?php

namespace Firebed\AadeMyData\Models;


/**
 * Στις περιπτώσεις που ο χρήστης καλέσει μια εκ των δυο μεθόδων λήψης δεδομένων
 * (RequestDocs, RequestTransmittedDocs), θα λάβει ένα αντικείμενο RequestedDoc
 * σε xml μορφή. Το αντικείμενο θα περιλαμβάνει λίστες παραστατικών, χαρακτηρισμών
 * εσόδων – εξόδων και ακυρώσεων παραστατικών οι οποίες έχουν mark μεγαλύτερο
 * από αυτό που εισήχθη ως παράμετρο, καθώς και το στοιχείο continuationToken,
 * σε περίπτωση που ο όγκος των δεδομένων υπερβαίνει το επιτρεπτό όριο και η
 * λήψη τους γίνει τμηματικά.
 *
 * <ul>
 * <li>Σε περίπτωση που θα επιστρέφεται το στοιχείο continuationToken τα πεδία
 * nextPartitionKey και nextRowKey θα είναι συμπληρωμένα από την υπηρεσία και
 * χρησιμοποιούνται στην επόμενη κλήση της ίδιας μεθόδου που είχε καλεστεί από
 * τον χρήστη.</li>
 * </ul>
 */
class RequestedDoc extends Type
{
    /**
     * @return ContinuationTokenType|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function getContinuationToken(): ?ContinuationTokenType
    {
        return $this->get('continuationToken');
    }

    /**
     * <h2>Στοιχείο για την τμηματική λήψη αποτελεσμάτων</h2>
     *
     * <p>Σε περίπτωση που θα επιστρέφεται το στοιχείο continuationToken τα πεδία
     * nextPartitionKey και nextRowKey θα είναι συμπληρωμένα από την υπηρεσία και
     * χρησιμοποιούνται στην επόμενη κλήση της ίδιας μεθόδου που είχε καλεστεί από
     * τον χρήστη.</p>l
     *
     * @param ContinuationTokenType $token
     * @return $this
     */
    public function setContinuationToken(ContinuationTokenType $token): self
    {
        return $this->put('continuationToken', $token);
    }

    /**
     * @return InvoicesDoc|null Λίστα Παραστατικών
     */
    public function getInvoicesDoc(): ?InvoicesDoc
    {
        return $this->get('invoicesDoc');
    }

    /**
     * <h2>Λίστα Παραστατικών</h2>
     *
     * @param InvoicesDoc $invoicesDoc
     * @return $this
     */
    public function setInvoicesDoc(InvoicesDoc $invoicesDoc): self
    {
        return $this->put('invoicesDoc', $invoicesDoc);
    }
}
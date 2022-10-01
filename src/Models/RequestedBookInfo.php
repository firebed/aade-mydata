<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

/**
 * Στις περιπτώσεις που ο χρήστης καλέσει μια εκ των δυο μεθόδων λήψης στοιχείων εσόδωνεξόδων (RequestMyIncome,RequestMyExpenses), όπως αυτές περιγράφονται σε
 * προηγούμενη παράγραφο, θα λάβει ένα αντικείμενο RequestedBookInfo σε xml μορφή. Το
 * αντικείμενο θα περιλαμβάνει λίστα στοιχείων εσόδων – εξόδων και ακυρώσεων
 * παραστατικών οι οποίες έχουν mark μεγαλύτερο από αυτό που εισήχθη ως παράμετρο,
 * καθώς και το στοιχείο continuationToken, σε περίπτωση που ο όγκος των δεδομένων
 * υπερβαίνει το επιτρεπτό όριο και η λήψη τους γίνει τμηματικά.
 */
class RequestedBookInfo extends Type implements IteratorAggregate, Countable
{
    use HasIterator;

    public function addInvoice(Invoice $invoice): void
    {
        $this->attributes[] = $invoice;
    }
}
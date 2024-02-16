<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

/**
 * Στις περιπτώσεις που ο χρήστης καλέσει μια εκ των δυο μεθόδων λήψης στοιχείων εσόδων/εξόδων
 * (RequestMyIncome, RequestMyExpenses), θα λάβει ένα αντικείμενο RequestedBookInfo σε xml μορφή.
 * Το αντικείμενο θα περιλαμβάνει λίστα στοιχείων εσόδων – εξόδων και ακυρώσεων
 * παραστατικών οι οποίες έχουν mark μεγαλύτερο από αυτό που εισήχθη ως παράμετρο,
 * καθώς και το στοιχείο continuationToken, σε περίπτωση που ο όγκος των δεδομένων
 * υπερβαίνει το επιτρεπτό όριο και η λήψη τους γίνει τμηματικά.
 */
class RequestedBookInfo extends Type implements IteratorAggregate, Countable
{
    use HasIterator;

    /**
     * @return ContinuationToken|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function getContinuationToken(): ?ContinuationToken
    {
        return $this->get('continuationToken');
    }

    /**
     * @return BookInfo[]|null
     */
    public function getBookInfo(): ?array
    {
        return $this->get('bookInfo');
    }

    public function set($key, $value): void
    {
        if ($key === 'bookInfo') {
            $this->push($key, $value);
            return;
        }

        parent::set($key, $value);
    }
}
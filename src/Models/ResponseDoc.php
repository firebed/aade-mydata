<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

/**
 * Στις περιπτώσεις που ο χρήστης χρησιμοποιήσει κάποια μέθοδο υποβολής στοιχείων ή
 * ακύρωση (SendInvoices, SendIncomeClassification, SendExpensesClassification,
 * CancelInvoice) θα λαμβάνει ως απάντηση ένα αντικείμενο ResponseDoc σε xml μορφή. Το
 * αντικείμενο περιλαμβάνει μια λίστα από στοιχεία τύπου response, ένα για κάθε οντότητα
 * που υποβλήθηκε.
 * 
 * @template TModel of Response
 */
class ResponseDoc extends Type implements IteratorAggregate, Countable, ArrayAccess
{
    use HasIterator;

    /**
     * @return TModel
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }
    
    /**
     * @return TModel[]
     */
    public function all(): array
    {
        return $this->attributes;
    }
}
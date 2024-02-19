<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Στις περιπτώσεις που ο χρήστης χρησιμοποιήσει κάποια μέθοδο υποβολής στοιχείων ή
 * ακύρωση (SendInvoices, SendIncomeClassification, SendExpensesClassification,
 * CancelInvoice) θα λαμβάνει ως απάντηση ένα αντικείμενο ResponseDoc σε xml μορφή. Το
 * αντικείμενο περιλαμβάνει μια λίστα από στοιχεία τύπου response, ένα για κάθε οντότητα
 * που υποβλήθηκε.
 *
 * @implements IteratorAggregate<int, Response>
 * @implements ArrayAccess<int, Response>
 */
class ResponseDoc extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    public array $casts = [
        'response' => Response::class,
    ];

    public function __construct(array $responses = [])
    {
        $this->attributes['response'] = $responses;
    }

    /**
     * @return Traversable<int, Response>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['response']);
    }
    
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes['response'][$offset]);
    }
    
    public function offsetGet(mixed $offset): Response
    {
        return $this->attributes['response'][$offset];
    }
    
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes['response'][$offset] = $value;
    }
    
    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes['response'][$offset]);
    }
    
    public function count(): int
    {
        return count($this->attributes['response']);
    }
}
<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * Στις περιπτώσεις που ο χρήστης χρησιμοποιήσει κάποια μέθοδο υποβολής στοιχείων ή
 * ακύρωση (SendInvoices, SendIncomeClassification, SendExpensesClassification,
 * CancelInvoice) θα λαμβάνει ως απάντηση ένα αντικείμενο ResponseDoc σε xml μορφή. Το
 * αντικείμενο περιλαμβάνει μια λίστα από στοιχεία τύπου response, ένα για κάθε οντότητα
 * που υποβλήθηκε.
 *
 * @extends ModelCollection<Response>
 */
class ResponseDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'response' => Arr::of(Response::class),
        ];
    }
}

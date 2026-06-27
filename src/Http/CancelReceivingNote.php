<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\ResponseDoc;

/**
 * <p>Αυτή η POST μέθοδος, που είναι διαθέσιμη μόνο για πιστοποιημένους παρόχους,
 * χρησιμοποιείται για την ακύρωση ενός Δελτίου Ποσοτικής Παραλαβής (τύπου 10.1 / 10.2).
 * Ο χρήστης την καλεί υποβάλλοντας ως παράμετρο το mark του δελτίου το οποίο θέλει να
 * ακυρώσει καθώς και την παράμετρο entityVatNumber που είναι το ΑΦΜ της οντότητας που
 * εξέδωσε το προς ακύρωση δελτίο. Δεν απαιτείται αποστολή xml body.</p>
 *
 * <p>Σε περίπτωση επιτυχίας η ακύρωση ως πράξη λαμβάνει το δικό της mark το οποίο
 * επιστρέφεται στον χρήστη και το δελτίο θεωρείται ακυρωμένο. Σε περίπτωση αποτυχίας
 * επιστρέφεται το αντίστοιχο μήνυμα λάθους.</p>
 *
 * @version 2.0.2
 */
class CancelReceivingNote extends CancelInvoice
{
    public function handle(string $mark, ?string $entityVatNumber = null): ResponseDoc
    {
        $this->ensureProvider();

        return parent::handle($mark, $entityVatNumber);
    }
}

<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\ResponseDoc;
use GuzzleHttp\Exception\GuzzleException;

/**
 * <p>Αυτή η POST μέθοδος χρησιμοποιείται για την ακύρωση παραστατικού χωρίς
 * επαναϋποβολή καινούργιου. Ο χρήστης την καλεί υποβάλλοντας ως παράμετρο το mark
 * του παραστατικού το οποίο θέλει να ακυρώσει. Για την περίπτωση εκείνη και μόνο που η
 * μέθοδος κληθεί από τρίτο πρόσωπο (όπως εκπρόσωπος Ν.Π. ή λογιστής), ο ΑΦΜ της
 * οντότητας που εξέδωσε το προς ακύρωση παραστατικό αποστέλλεται μέσω της
 * παραμέτρου entityVatNumber, διαφορετικά η εν λόγω παράμετρος δε χρειάζεται να
 * αποσταλεί.</p>
 *
 * <p>Σε περίπτωση επιτυχίας η ακύρωση ως πράξη λαμβάνει το δικό της mark το οποίο
 * επιστρέφεται στον χρήστη και το παραστατικό θεωρείται ακυρωμένο. Σε περίπτωση
 * αποτυχίας επιστρέφεται το αντίστοιχο μήνυμα λάθους.</p>
 */
class CancelInvoice extends MyDataRequest
{
    /**
     * @param string      $mark            Μοναδικός αριθμός καταχώρησης παραστατικού προς ακύρωση
     * @param string|null $entityVatNumber ΑΦΜ οντότητας
     * @throws GuzzleException
     */
    public function handle(string $mark, string $entityVatNumber = null): ResponseDoc
    {
        $query = ['mark' => $mark];
        
        if (!empty($entityVatNumber)) {
            $query['entityVatNumber'] = $entityVatNumber;
        }

        return $this->post($query);
    }
}
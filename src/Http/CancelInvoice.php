<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\ResponseDocReader;

/**
 * <p>Αυτή η POST μέθοδος χρησιμοποιείται για την ακύρωση παραστατικού χωρίς
 * επαναϋποβολή καινούργιου. Ο χρήστης την καλεί υποβάλλοντας ως παράμετρο το mark
 * του παραστατικού το οποίο θέλει να ακυρώσει. Για την περίπτωση εκείνη και μόνο που η
 * μέθοδος κληθεί από τρίτο πρόσωπο (όπως εκπρόσωπος Ν.Π., ή λογιστής), ο ΑΦΜ της
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
    use HasResponseDom;

    /**
     * @param  string  $mark  Μοναδικός αριθμός καταχώρησης παραστατικού προς ακύρωση
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     *
     * @throws MyDataException
     */
    public function handle(string|int $mark, string|int|null $entityVatNumber = null): ResponseDoc
    {
        $query = $this->filterArray(['mark' => $mark, 'entityVatNumber' => $entityVatNumber]);

        return $this->read($this->post($query), new ResponseDocReader);
    }
}

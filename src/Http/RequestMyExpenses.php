<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\RequestedBookInfo;
use GuzzleHttp\Exception\GuzzleException;

class RequestMyExpenses extends MyDataRequest
{
    /**
     * <p>Η κλήση επιστρέφει γραμμές με πληροφορίες για τα έξοδα του χρήστη, για συγκεκριμένο
     * ημερολογιακό κλειστό διάστημα που ορίζεται από τις τιμές των παραμέτρων dateFrom και
     * dateTo. Προαιρετικά η αναζήτηση μπορεί να πραγματοποιηθεί με επιπλέον φίλτρα
     * συγκεκριμένο ΑΦΜ αντισυμβαλλόμενου και συγκεκριμένο τύπο παραστατικού.</p>
     *
     * <ol>
     * <li>Αν η παράμετρος entityVatNumber έχει τιμή, η αναζήτηση θα πραγματοποιηθεί για
     * αυτόν τον ΑΦΜ, αλλιώς για τον ΑΦΜ του χρήστη που καλεί την μέθοδο.</li>
     *
     * <li>Οι παράμετροι ημερομηνιών πρέπει να εισαχθούν με format dd/MM/yyyy.</li>
     *
     * <li>Όταν μια προαιρετική παράμετρος δεν εισάγεται, η αναζήτηση πραγματοποιείται
     * για όλες τις πιθανές τιμές που θα μπορούσε να έχει αυτό το πεδίο.</li>
     *
     * <li>Στην περίπτωση που τα αποτελέσματα αναζήτησης ξεπερνούν σε μέγεθος το
     * μέγιστο επιτρεπτό όριο ο χρήστης θα τα λάβει τμηματικά. Τα πεδία
     * nextPartitionKey και nextRowKey θα εμπεριέχονται σε κάθε τμήμα των
     * αποτελεσμάτων και θα χρησιμοποιούνται ως παράμετροι στην κλήση για τη λήψη
     * του επόμενου τμήματος αποτελεσμάτων.</li>
     * </ol>
     *
     * @param string      $dateFrom         Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param string      $dateTo           Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param string|null $counterVatNumber ΑΦΜ αντισυμβαλλόμενου
     * @param string|null $entityVatNumber  ΑΦΜ οντότητας
     * @param string|null $invType          Τύπος παραστατικού
     * @param string|null $nextPartitionKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param string|null $nextRowKey       Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedBookInfo
     * @throws GuzzleException
     */
    public function handle(string $dateFrom, string $dateTo, string $counterVatNumber = null, string $entityVatNumber = null, string $invType = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedBookInfo
    {
        $query = compact('dateFrom', 'dateTo', 'counterVatNumber', 'entityVatNumber', 'invType', 'nextPartitionKey', 'nextRowKey');
        $query = array_filter($query);

        return $this->get($query);
    }
}
<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasResponseXML;
use Firebed\AadeMyData\Models\RequestedVatInfo;
use Firebed\AadeMyData\Xml\VatInfoReader;

/**
 * Στις περιπτώσεις που ο χρήστης καλέσει τη μέθοδο λήψης πληροφοριών για στοιχεία
 * ΦΠΑ (RequestVatInfo), θα λάβει ένα αντικείμενο RequestedVatInfo σε xml μορφή.
 * Το αντικείμενο θα περιλαμβάνει λίστα στοιχείων εισροών και εκροών ανά παραστατικό
 * (VatInfo) για τον ΑΦΜ που εισήχθη ως παράμετρο, καθώς και το στοιχείο continuationToken,
 * σε περίπτωση που ο όγκος των δεδομένων υπερβαίνει το επιτρεπτό όριο και η λήψη τους
 * γίνει τμηματικά.
 *
 * @version 1.0.8
 */
class RequestVatInfo extends MyDataRequest
{
    use HasResponseXML;

    /**
     * Αυτή η μέθοδος επιτρέπει στον χρήστη να λαμβάνει λεπτομερείς πληροφορίες για τα
     * στοιχεία ΦΠΑ που συνδέονται με τον ΑΦΜ μιας οντότητας για ένα συγκεκριμένο χρονικό
     * διάστημα. Η μέθοδος μπορεί να χρησιμοποιηθεί για την ανάκτηση πληροφοριών για τις
     * εγγραφές ΦΠΑ ενός προσώπου ή επιχείρησης.
     *
     * @param string $dateFrom Αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param string $dateTo Τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param string|null $entityVatNumber ΑΦΜ οντότητας
     * @param string|null $nextPartitionKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param string|null $nextRowKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedVatInfo
     * @throws MyDataException
     * @version 1.0.8
     */
    public function handle(string $dateFrom, string $dateTo, string $entityVatNumber = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedVatInfo
    {
        $query = compact('dateFrom', 'dateTo', 'entityVatNumber', 'nextPartitionKey', 'nextRowKey');
        $query = array_filter($query);

        $response = $this->get($query);
        $this->responseXML = $response->getBody()->getContents();

        return (new VatInfoReader())->parseXML($this->responseXML);
    }
}
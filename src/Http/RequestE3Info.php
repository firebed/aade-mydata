<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\RequestedE3Info;
use Firebed\AadeMyData\Xml\E3InfoReader;
use Generator;

/**
 * Στις περιπτώσεις που ο χρήστης καλέσει τη μέθοδο λήψης πληροφοριών για στοιχεία
 * E3 (RequestE3Info), θα λάβει ένα αντικείμενο RequestedE3Info σε xml μορφή.
 * Το αντικείμενο θα περιλαμβάνει λίστα στοιχείων E3 ανά παραστατικό
 * (E3Info) για τον ΑΦΜ που εισήχθη ως παράμετρο, καθώς και το στοιχείο continuationToken,
 * σε περίπτωση που ο όγκος των δεδομένων υπερβαίνει το επιτρεπτό όριο και η λήψη τους
 * γίνει τμηματικά.
 *
 * @version 1.0.10
 */
class RequestE3Info extends MyDataRequest
{
    use HasResponseDom;

    /**
     * Αυτή η μέθοδος επιτρέπει στον χρήστη να λαμβάνει λεπτομερείς πληροφορίες για τα
     * στοιχεία Ε3 που συνδέονται με τον ΑΦΜ μιας οντότητας για ένα συγκεκριμένο χρονικό
     * διάστημα. Η μέθοδος μπορεί να χρησιμοποιηθεί για την ανάκτηση πληροφοριών για τις
     * εγγραφές Ε3 ενός προσώπου ή επιχείρησης, είτε ανά τιμολόγιο είτε ανά ημέρα, ανάλογα με
     * τις παραμέτρους που έχουν δοθεί.
     *
     * @param  string  $dateFrom  Αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param  string  $dateTo  Τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης (μορφή dd/MM/yyyy)
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  bool  $groupedPerDay  Παράμετρος που δηλώνει εάν τα αποτελέσματα πρέπει να ομαδοποιηθούν ανά ημέρα.
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων <code>($groupedPerDay = false)</code>
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων <code>($groupedPerDay = false)</code>
     *
     * @throws MyDataException
     * @throws MyDataAuthenticationException
     *
     * @version 1.0.10
     */
    public function handle(string $dateFrom, string $dateTo, ?string $entityVatNumber = null, bool $groupedPerDay = false, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedE3Info
    {
        $query = $this->filterArray([
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'entityVatNumber' => $entityVatNumber,
            'GroupedPerDay' => $groupedPerDay ? 'true' : 'false',
            'nextPartitionKey' => $nextPartitionKey,
            'nextRowKey' => $nextRowKey,
        ]);

        return $this->read($this->get($query), new E3InfoReader);
    }

    /**
     * Every page of the results, following the continuation token myDATA returns when
     * the results exceed its size limit (myDATA pages only when not grouped per day).
     * Pass $nextPartitionKey and $nextRowKey to resume from a page's continuation token.
     *
     * @return Generator<int, RequestedE3Info>
     *
     * @throws MyDataException
     */
    public function pages(string $dateFrom, string $dateTo, ?string $entityVatNumber = null, bool $groupedPerDay = false, ?string $nextPartitionKey = null, ?string $nextRowKey = null): Generator
    {
        return $this->paginate(fn (?string $npk, ?string $nrk) => $this->handle($dateFrom, $dateTo, $entityVatNumber, $groupedPerDay, $npk, $nrk), $nextPartitionKey, $nextRowKey);
    }
}

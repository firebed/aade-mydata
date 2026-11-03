<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\RequestedBookInfo;
use Firebed\AadeMyData\Xml\BookInfoReader;
use Generator;

abstract class RequestBookInfo extends MyDataRequest
{
    use HasResponseDom;

    /**
     * <p>Η κλήση επιστρέφει γραμμές με πληροφορίες για τα έσοδα/έξοδα του χρήστη, για συγκεκριμένο
     * ημερολογιακό κλειστό διάστημα που ορίζεται από τις τιμές των παραμέτρων dateFrom και
     * dateTo. Προαιρετικά η αναζήτηση μπορεί να πραγματοποιηθεί με επιπλέον φίλτρα
     * συγκεκριμένο ΑΦΜ αντισυμβαλλόμενου και συγκεκριμένο τύπο παραστατικού.</p>
     *
     * <ol>
     * <li>Αν η παράμετρος entityVatNumber έχει τιμή, η αναζήτηση θα πραγματοποιηθεί για
     * αυτόν τον ΑΦΜ, αλλιώς για τον ΑΦΜ του χρήστη που καλεί τη μέθοδο.</li>
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
     * @param  string  $dateFrom  Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string  $dateTo  Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param  string|null  $counterVatNumber  ΑΦΜ αντισυμβαλλόμενου
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     * @param  InvoiceType|string|null  $invType  Τύπος παραστατικού
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     *
     * @throws MyDataException
     */
    public function handle(string $dateFrom, string $dateTo, ?string $counterVatNumber = null, ?string $entityVatNumber = null, InvoiceType|string|null $invType = null, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedBookInfo
    {
        $query = $this->filterArray(compact('dateFrom', 'dateTo', 'counterVatNumber', 'entityVatNumber', 'invType', 'nextPartitionKey', 'nextRowKey'));

        return $this->read($this->get($query), new BookInfoReader);
    }

    /**
     * Every page of the results, following the continuation token myDATA returns when
     * the results exceed its size limit. Pages are fetched lazily as they are iterated.
     *
     * myDATA throttles the document requests of a subscription key by the number of calls
     * in a window of about five minutes, so a long run either spaces its calls
     * (Connection::withThrottle()), sits out the window (Connection::withRateLimitWait()),
     * or catches RateLimitExceededException and resumes later from the last page's
     * continuation token through $nextPartitionKey and $nextRowKey.
     *
     * @return Generator<int, RequestedBookInfo>
     *
     * @throws MyDataException
     */
    public function pages(string $dateFrom, string $dateTo, ?string $counterVatNumber = null, ?string $entityVatNumber = null, InvoiceType|string|null $invType = null, ?string $nextPartitionKey = null, ?string $nextRowKey = null): Generator
    {
        return $this->paginate(fn (?string $npk, ?string $nrk) => $this->handle($dateFrom, $dateTo, $counterVatNumber, $entityVatNumber, $invType, $npk, $nrk), $nextPartitionKey, $nextRowKey);
    }
}

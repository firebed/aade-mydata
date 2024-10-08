<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\RequestedBookInfo;
use Firebed\AadeMyData\Xml\BookInfoReader;

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
     * @param string $dateFrom Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param string $dateTo Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param string|null $counterVatNumber ΑΦΜ αντισυμβαλλόμενου
     * @param string|null $entityVatNumber ΑΦΜ οντότητας
     * @param InvoiceType|string|null $invType Τύπος παραστατικού
     * @param string|null $nextPartitionKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param string|null $nextRowKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedBookInfo
     * @throws MyDataException
     */
    public function handle(string $dateFrom, string $dateTo, string $counterVatNumber = null, string $entityVatNumber = null, InvoiceType|string $invType = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedBookInfo
    {
        $invType = $invType instanceof InvoiceType ? $invType->value : $invType;
        
        $query = compact('dateFrom', 'dateTo', 'counterVatNumber', 'entityVatNumber', 'invType', 'nextPartitionKey', 'nextRowKey');
        $query = array_filter($query);

        // Get the response XML
        $responseXML = $this->get($query)->getBody()->getContents();

        if (empty($responseXML)) {
            throw new MyDataException('Invalid response from MyData API');
        }
        // Parse the response XML
        $reader = new BookInfoReader();
        $bookInfo = $reader->parseXML($responseXML);
        
        $this->responseDom = $reader->getDomDocument();
        
        return $bookInfo;
    }
}
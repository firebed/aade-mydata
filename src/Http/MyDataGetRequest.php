<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Xml\RequestedDocReader;

abstract class MyDataGetRequest extends MyDataRequest
{
    use HasResponseDom;

    /**
     * <ol>
     * <li>Στην περίπτωση που τα αποτελέσματα αναζήτησης ξεπερνούν σε μέγεθος το
     * μέγιστο επιτρεπτό όριο ο χρήστης θα τα λάβει τμηματικά. Τα πεδία
     * nextPartitionKey και nextRowKey θα εμπεριέχονται σε κάθε τμήμα των
     * αποτελεσμάτων και θα χρησιμοποιούνται ως παράμετροι στην κλήση για τη λήψη
     * του επόμενου τμήματος αποτελεσμάτων.</li>
     *
     * <li>Σε περίπτωση που κάποια εκ των παραπάνω παραμέτρων δεν έχει τιμή, η
     * αναζήτηση πραγματοποιείται για όλες τις πιθανές τιμές αυτού του πεδίου, όπως
     * προηγουμένως.</li>
     *
     * <li>Σε περίπτωση που μόνο μια εκ των dateFrom, dateTo παραληφθεί, η αναζήτηση θα
     * εκτελεστεί μόνο για την ημερομηνία που έχει δοθεί στην άλλη παράμετρο. Αν και οι
     * παράμετροι έχουν τιμή, η αναζήτηση θα εκτελεστεί για το διάστημα από dateFrom
     * έως dateTo.</li>
     *
     * <li>Εφόσον αποδοθεί τιμή στην παράμετρο maxMark, θα επιστραφούν όσες εγγραφές
     * έχουν ΜΑΡΚ μικρότερο ή ίσο αυτή της τιμής.</li>
     *
     * <li>Οι τιμές των παραμέτρων receiverVatNumber και invType εφαρμόζονται πάντα με
     * τον συντελεστή ισότητας (equal operator).</li>
     *
     * <li>Στην παράμετρο invType δίνεται ως τιμή ο αριθμός που αντιστοιχεί στον
     * συγκεκριμένο τύπο σύμφωνα με τον πίνακα 8.1 του Παραρτήματος.</li>
     * </ol>
     *
     * @param string $mark Μοναδικός αριθμός καταχώρησης
     * @param string|null $dateFrom Η αρχή χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param string|null $dateTo Το τέλος χρονικού διαστήματος αναζήτησης για την ημερομηνία έκδοσης dd/MM/yyyy
     * @param string|null $receiverVatNumber ΑΦΜ αντισυμβαλλόμενου
     * @param string|null $entityVatNumber ΑΦΜ οντότητας
     * @param InvoiceType|string|null $invType Τύπος παραστατικού
     * @param string|null $maxMark Μέγιστος Αριθμός ΜΑΡΚ
     * @param string|null $nextPartitionKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param string|null $nextRowKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @return RequestedDoc
     * @throws MyDataException
     */
    public function handle(string $mark = '', string $dateFrom = null, string $dateTo = null, string $receiverVatNumber = null, string $entityVatNumber = null, InvoiceType|string $invType = null, string $maxMark = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedDoc
    {
        $query = compact('mark');

        $invType = $invType instanceof InvoiceType ? $invType->value : $invType;
        
        $params = compact('dateFrom', 'dateTo', 'receiverVatNumber', 'entityVatNumber', 'invType', 'maxMark', 'nextPartitionKey', 'nextRowKey');
        
        // Merge query with params
        $query = array_merge($query, array_filter($params));
        
        // Get the response XML
        $responseXML = $this->get($query);

        // Parse the response XML
        $reader = new RequestedDocReader();
        $responseDoc = $reader->parseXML($responseXML);
        
        $this->responseDom = $reader->getDomDocument();
        
        return $responseDoc;
    }
}
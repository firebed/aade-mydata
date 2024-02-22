<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

class Response extends Type
{
    use HasFactory;

    protected array $casts = [
        'errors' => Errors::class,
    ];

    /**
     * @return int|null Αριθμός Σειράς Οντότητας εντός του υποβληθέντος xml
     */
    public function getIndex(): ?int
    {
        return $this->get('index');
    }

    public function setIndex(int $index): void
    {
        $this->set('index', $index);
    }

    /**
     * <p>Καθορίζει το είδος της απάντησης (πετυχημένη ή αποτυχημένη διαδικασία).</p>
     *
     * <p>Σε περίπτωση επιτυχίας το πεδίο statusCode έχει τιμή Success και η απάντηση
     * περιλαμβάνει τις αντίστοιχες τιμές για τα πεδία invoiceUid, invoiceMark,
     * classificationMark και cancellationMark, ανάλογα με την οντότητα που υποβλήθηκε.</p>
     *
     * <p>Σε περίπτωση αποτυχίας το πεδίο statusCode έχει τιμή αντίστοιχη του είδους του
     * σφάλματος και η απάντηση περιλαμβάνει μια λίστα στοιχείων σφάλματος τύπου
     * ErrorType για κάθε οντότητα που η υποβολή της απέτυχε. Όλα τα στοιχεία σφάλματος
     * ανά οντότητα είναι υποχρεωτικά της ίδιας κατηγορίας που χαρακτηρίζει την απάντηση.</p>
     *
     * <ul>Πιθανές τιμές
     * <li>Success</li>
     * <li>ValidationError</li>
     * <li>TechnicalError</li>
     * <li>XMLSyntaxError</li>
     * </ul>
     * @return string|null Κωδικός Αποτελέσματος
     */
    public function getStatusCode(): ?string
    {
        return $this->get('statusCode');
    }

    public function setStatusCode(string $statusCode): void
    {
        $this->set('statusCode', $statusCode);
    }

    /**
     * Επιστρέφει μόνο στην περίπτωση που η υποβολή αφορούσε παραστατικό.
     *
     * @return string|null Αναγνωριστικό Παραστατικού
     */
    public function getInvoiceUid(): ?string
    {
        return $this->get('invoiceUid');
    }

    public function setInvoiceUid(string $invoiceUid): void
    {
        $this->set('invoiceUid', $invoiceUid);
    }

    /**
     * Περιέχει το mark του υποβληθέντος παραστατικού στην
     * περίπτωση που υποβλήθηκαν παραστατικά και το mark του παραστατικού που
     * αφορούσαν οι υποβληθέντες χαρακτηρισμοί, στην περίπτωση υποβολής
     * χαρακτηρισμών.
     *
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getInvoiceMark(): ?string
    {
        return $this->get('invoiceMark');
    }

    public function setInvoiceMark(string $invoiceMark): void
    {
        $this->set('invoiceMark', $invoiceMark);
    }

    /**
     * Επιστρέφει μόνο στην περίπτωση που η υποβολή αφορούσε χαρακτηρισμό.
     *
     * @return string|null Μοναδικός Αριθμός Παραλαβής Χαρακτηρισμού
     */
    public function getClassificationMark(): ?string
    {
        return $this->get('classificationMark');
    }

    public function setClassificationMark(string $classificationMark): void
    {
        $this->set('classificationMark', $classificationMark);
    }

    /**
     * Επιστρέφει στην περίπτωση που η υποβολή έγινε μέσω παρόχου.
     *
     * @return string|null Συμβολοσειρά Αυθεντικοποίησης
     */
    public function getAuthenticationCode(): ?string
    {
        return $this->get('authenticationCode');
    }

    public function setAuthenticationCode(string $authenticationCode): void
    {
        $this->set('authenticationCode', $authenticationCode);
    }

    /**
     * Επιστρέφει μόνο στην περίπτωση που η υποβολή αφορούσε ακύρωση παραστατικού.
     *
     * @return string|null Μοναδικός Αριθμός Ακύρωσης
     */
    public function getCancellationMark(): ?string
    {
        return $this->get('cancellationMark');
    }

    public function setCancellationMark(string $cancellationMark): void
    {
        $this->set('cancellationMark', $cancellationMark);
    }

    public function hasErrors(): bool
    {
        return !is_null($this->getErrors()) && count($this->getErrors()) > 0;
    }

    /**
     * @return string|null Το πεδίο qrUrl επιστρέφει μόνο στις υποβολές παραστατικών τύπου από 1.1 έως 11.5
     * @version 1.0.7
     */
    public function getQrUrl(): ?string
    {
        return $this->get('qrUrl');
    }

    /**
     * Χρησιμοποιείται από τα προγράμματα για τη δημιουργία QR Code τύπου Url
     *
     * @param $qrUrl
     * @version 1.0.7
     */
    public function setQrUrl($qrUrl): void
    {
        $this->set('qrUrl', $qrUrl);
    }

    /**
     * @return Errors|null Λίστα Σφαλμάτων
     */
    public function getErrors(): ?Errors
    {
        return $this->get('errors');
    }

    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === 'Success';
    }
}
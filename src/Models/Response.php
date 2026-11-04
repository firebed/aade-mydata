<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

class Response extends Model
{
    use HasFactory;

    /**
     * Error 228 as myDATA words it: "The UID: {uid} is invalid . It has already been sent for
     * another invoice (MARK:{mark} , AUTHENTICATION_CODE:{code})", the code left out while
     * the invoice is still queued for storage.
     */
    private const string DUPLICATE = '/UID:\s*([A-F0-9]+).*?already been sent for another invoice\s*\(\s*MARK:\s*(\d+)\s*(?:,\s*AUTHENTICATION_CODE:\s*([A-F0-9]*))?\s*\)/is';

    protected function casts(): array
    {
        return [
            'errors' => Errors::class,
            'receptionEmails' => ReceptionEmails::class,
            'receptionProviders' => ReceptionProviders::class,
        ];
    }

    /**
     * @return int|null Αριθμός Σειράς Οντότητας εντός του υποβληθέντος xml
     */
    public function getIndex(): ?int
    {
        return $this->get('index');
    }

    public function setIndex(int $index): static
    {
        return $this->set('index', $index);
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
     *
     * @return string|null Κωδικός Αποτελέσματος
     */
    public function getStatusCode(): ?string
    {
        return $this->get('statusCode');
    }

    public function setStatusCode(string $statusCode): static
    {
        return $this->set('statusCode', $statusCode);
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

    public function setInvoiceUid(string $invoiceUid): static
    {
        return $this->set('invoiceUid', $invoiceUid);
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

    public function setInvoiceMark(string $invoiceMark): static
    {
        return $this->set('invoiceMark', $invoiceMark);
    }

    public function getPaymentMethodMark(): ?string
    {
        return $this->get('paymentMethodMark');
    }

    public function setPaymentMethodMark(?string $paymentMethodMark): static
    {
        return $this->set('paymentMethodMark', $paymentMethodMark);
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

    public function setClassificationMark(string $classificationMark): static
    {
        return $this->set('classificationMark', $classificationMark);
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

    public function setAuthenticationCode(string $authenticationCode): static
    {
        return $this->set('authenticationCode', $authenticationCode);
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

    public function setCancellationMark(string $cancellationMark): static
    {
        return $this->set('cancellationMark', $cancellationMark);
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()?->isNotEmpty() ?? false;
    }

    /**
     * @return string|null Το πεδίο qrUrl επιστρέφει μόνο στις υποβολές παραστατικών τύπου από 1.1 έως 11.5
     *
     * @version 1.0.7
     */
    public function getQrUrl(): ?string
    {
        return $this->get('qrUrl');
    }

    /**
     * Χρησιμοποιείται από τα προγράμματα για τη δημιουργία QR Code τύπου Url
     *
     *
     * @version 1.0.7
     */
    public function setQrUrl(?string $qrUrl): static
    {
        return $this->set('qrUrl', $qrUrl);
    }

    /**
     * @return Errors|null Λίστα Σφαλμάτων
     */
    public function getErrors(): ?Errors
    {
        return $this->get('errors');
    }

    public function getReceptionEmails(): ?ReceptionEmails
    {
        return $this->get('receptionEmails');
    }

    /**
     * @return ReceptionProviders|null Λίστα Παρόχων του λήπτη (αποστολή μέσω παρόχου)
     */
    public function getReceptionProviders(): ?ReceptionProviders
    {
        return $this->get('receptionProviders');
    }

    /**
     * The invoice myDATA already holds under this invoice's uid, when it rejected the
     * invoice as sent before (error 228, "It has already been sent for another invoice").
     * Its mark settles a resend after a lost answer; its authentication code is null
     * until myDATA has stored that invoice, which can take hours.
     *
     * Null for any other answer. On the provider sandbox, a resend dated a past day was
     * rejected for its issue date (error 238) alone, without a 228 naming the mark.
     */
    public function getDuplicate(): ?Duplicate
    {
        foreach ($this->getErrors()?->all() ?? [] as $error) {
            if ($error->getCode() === '228' && preg_match(self::DUPLICATE, (string) $error->getMessage(), $m) === 1) {
                return new Duplicate(strtoupper($m[1]), (int) $m[2], ($m[3] ?? '') !== '' ? strtoupper($m[3]) : null);
            }
        }

        return null;
    }

    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === 'Success';
    }
}

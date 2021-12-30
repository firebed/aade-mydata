<?php

namespace Firebed\AadeMyData\Models;

/**
 * <ul>
 * <li>Το είδος της απάντησης (πετυχημένη ή αποτυχημένη διαδικασία) καθορίζεται από την
 * τιμή του πεδίου statusCode.</li>
 *
 * <li>Σε περίπτωση επιτυχίας το πεδίο statusCode έχει τιμή Success και η απάντηση
 * περιλαμβάνει τις αντίστοιχες τιμές για τα πεδία invoiceUid, invoiceMark,
 * classificationMark και cancellationMark, ανάλογα με την οντότητα που υποβλήθηκε.</li>
 *
 * <li>Σε περίπτωση αποτυχίας το πεδίο statusCode έχει τιμή αντίστοιχη του είδους του
 * σφάλματος και η απάντηση περιλαμβάνει μια λίστα στοιχείων σφάλματος τύπου
 * ErrorType για κάθε οντότητα που η υποβολή της απέτυχε. Όλα τα στοιχεία σφάλματος
 * ανά οντότητα είναι υποχρεωτικά της ίδιας κατηγορίας που χαρακτηρίζει την απάντηση.</li>
 *
 * <li>Το πεδίο <strong>invoiceUid</strong> επιστρέφει μόνο στην περίπτωση που η υποβολή αφορούσε
 * παραστατικό.</li>
 *
 * <li>Το πεδίο <strong>classificationMark</strong> επιστρέφει μόνο στην περίπτωση που η υποβολή
 * αφορούσε χαρακτηρισμό.</li>
 *
 * <li>Το πεδίο <strong>authenticationCode</strong> επιστρέφει στην περίπτωση που η υποβολή έγινε μέσω
 * παρόχου.</li>
 *
 * <li>Το πεδίο <strong>cancellationMark</strong> επιστρέφει μόνο στην περίπτωση που η υποβολή αφορούσε
 * ακύρωση παραστατικού.</li>
 *
 * <li>Το πεδίο <strong>invoiceMark</strong> περιέχει το mark του υποβληθέντος παραστατικού στην
 * περίπτωση που υποβλήθηκαν παραστατικά και το mark του παραστατικού που
 * αφορούσαν οι υποβληθέντες χαρακτηρισμοί, στην περίπτωση υποβολής
 * χαρακτηρισμών.</li>
 * </ul>
 */
class ResponseType extends Type
{
    /**
     * @return int|null Αριθμός Σειράς Οντότητας εντός του υποβληθέντος xml
     */
    public function getIndex(): ?int
    {
        return $this->get('index');
    }

    /**
     * <h2>Αριθμός Σειράς Οντότητας εντός του υποβληθέντος xml</h2>
     *
     * @param int $index
     * @return $this
     */
    public function setIndex(int $index): self
    {
        return $this->put('index', $index);
    }

    /**
     * @return string Κωδικός Αποτελέσματος [Success, ValidationError, TechnicalError, XMLSyntaxError]
     */
    public function getStatusCode(): string
    {
        return $this->get('statusCode');
    }

    /**
     * <h2>Κωδικός Αποτελέσματος</h2>
     *
     * <p>Αποδεκτές τιμές [Success, ValidationError, TechnicalError, XMLSyntaxError]</p>
     *
     * @param string $statusCode
     * @return $this
     */
    public function setStatusCode(string $statusCode): self
    {
        return $this->put('statusCode', $statusCode);
    }

    /**
     * @return string|null Αναγνωριστικό Παραστατικού
     */
    public function getInvoiceUid(): ?string
    {
        return $this->get('invoiceUid');
    }

    /**
     * <h2>Αναγνωριστικό Παραστατικού</h2>
     *
     * <p>Το πεδίο invoiceUid επιστρέφει μόνο στην περίπτωση που η υποβολή αφορούσε
     * παραστατικό.</p>
     *
     * @param string $invoiceUid Μήκος = 40
     * @return $this
     */
    public function setInvoiceUid(string $invoiceUid): self
    {
        return $this->put('invoiceUid', $invoiceUid);
    }

    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getInvoiceMark(): ?string
    {
        return $this->get('invoiceMark');
    }

    /**
     * <h2>Μοναδικός Αριθμός Καταχώρησης Παραστατικού</h2>
     *
     * <p>Το πεδίο invoiceMark περιέχει το mark του υποβληθέντος παραστατικού στην
     * περίπτωση που υποβλήθηκαν παραστατικά και το mark του παραστατικού που
     * αφορούσαν οι υποβληθέντες χαρακτηρισμοί, στην περίπτωση υποβολής
     * χαρακτηρισμών.</p>
     *
     * @param string $invoiceMark
     * @return $this
     */
    public function setInvoiceMark(string $invoiceMark): self
    {
        return $this->put('invoiceMark', $invoiceMark);
    }

    /**
     * @return string|null Μοναδικός Αριθμός Παραλαβής Χαρακτηρισμού
     */
    public function getClassificationMark(): ?string
    {
        return $this->get('classificationMark');
    }

    /**
     * <h2>Μοναδικός Αριθμός Παραλαβής Χαρακτηρισμού</h2>
     *
     * <p>Το πεδίο classificationMark επιστρέφει μόνο στην περίπτωση που η υποβολή
     * αφορούσε χαρακτηρισμό.</p>
     *
     * @param string $classificationMark
     * @return $this
     */
    public function setClassificationMark(string $classificationMark): self
    {
        return $this->put('classificationMark', $classificationMark);
    }

    /**
     * @return string|null Συμβολοσειρά Αυθεντικοποίησης
     */
    public function getAuthenticationCode(): ?string
    {
        return $this->get('authenticationCode');
    }

    /**
     * <h2>Συμβολοσειρά Αυθεντικοποίησης</h2>
     *
     * <p>Το πεδίο authenticationCode επιστρέφει στην περίπτωση που η υποβολή έγινε μέσω
     * παρόχου.</p>
     *
     * @param string $authenticationCode
     * @return $this
     */
    public function setAuthenticationCode(string $authenticationCode): self
    {
        return $this->put('authenticationCode', $authenticationCode);
    }

    /**
     * @return string|null Μοναδικός Αριθμός Ακύρωσης
     */
    public function getCancellationMark(): ?string
    {
        return $this->get('cancellationMark');
    }

    /**
     * <h2>Μοναδικός Αριθμός Ακύρωσης</h2>
     *
     * <p>Το πεδίο cancellationMark επιστρέφει μόνο στην περίπτωση που η υποβολή αφορούσε
     * ακύρωση παραστατικού.</p>
     *
     * @param string $cancellationMark
     * @return $this
     */
    public function setCancellationMark(string $cancellationMark): self
    {
        return $this->put('cancellationMark', $cancellationMark);
    }

    /**
     * @return Errors Λίστα Σφαλμάτων
     */
    public function getErrors(): Errors
    {
        return $this->get('errors');
    }

    /**
     * <h2>Λίστα Σφαλμάτων</h2>
     * @param Errors $errors
     * @return $this
     */
    public function setErrors(Errors $errors): self
    {
        return $this->put('errors', $errors);
    }
}
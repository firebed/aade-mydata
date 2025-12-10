<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\Errors;
use Firebed\AadeMyData\Models\Type;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * @version 1.0.12
 */
class StatementResponse extends Type
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
     * @return string|null Κωδικός Αποτελέσματος
     */

    public function getStatementId(): ?string
    {
        return $this->get('statementId');
    }

    public function getRecallId(): ?string
    {
        return $this->get('recallId');
    }

    public function setStatusCode(string $statusCode): static
    {
        return $this->set('statusCode', $statusCode);
    }

    public function hasErrors(): bool
    {
        return !is_null($this->getErrors()) && count($this->getErrors()) > 0;
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

    public function getStatusCode(): ?string
    {
        return $this->get('statusCode');
    }

    public function __toString(): string
    {
        return $this->getStatusCode();
    }
}
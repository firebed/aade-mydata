<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Models\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Models\Enums\ExpenseClassificationCode;

/**
 * <p>Ο τύπος ExpensesClassificationType αποτελεί τη βασική δομή
 * του Χαρακτηρισμού Εξόδων και εμπεριέχεται είτε</p>
 * <ul>
 * <li>σε κάθε γραμμή του παραστατικού ξεχωριστά (χαρακτηρισμός γραμμής)</li>
 * <li>στην περίληψη παραστατικού (άθροισμα χαρακτηρισμών ανά τύπο - κατηγορία)</li>
 * <li>στο αντικείμενο InvoiceExpensesClassificationType όταν οι χαρακτηρισμοί εσόδων
 * υποβάλλονται ξεχωριστά μέσω της κλήσης SendExpensesClassification (βλ παράγραφος 4.3.3)</li>
 */
class ExpensesClassificationType extends Type
{
    /**
     * @return string|null Κωδικός Χαρακτηρισμού
     */
    public function getClassificationType(): ?string
    {
        return $this->get('classificationType');
    }

    /**
     * <h2>Κωδικός Χαρακτηρισμού</h2>
     *
     * <p>Οι τιμές περιγράφονται αναλυτικά στους αντίστοιχους πίνακες
     * του Παραρτήματος.</p>
     *
     * @param string|null $classificationType Κωδικός Χαρακτηρισμού
     * @see ExpenseClassificationCode
     */
    public function setClassificationType(?string $classificationType): self
    {
        return $this->put('classificationType', $classificationType);
    }

    /**
     * @return string Κατηγορία Χαρακτηρισμού
     */
    public function getClassificationCategory(): string
    {
        return $this->get('classificationCategory');
    }

    /**
     * <h2>Κατηγορία Χαρακτηρισμού</h2>
     *
     * <p>Οι τιμές περιγράφονται αναλυτικά στους αντίστοιχους πίνακες
     * του Παραρτήματος.</p>
     *
     * @param string $classificationCategory Κατηγορία Χαρακτηρισμού
     * @see ExpenseClassificationCategory
     */
    public function setClassificationCategory(string $classificationCategory): self
    {
        return $this->put('classificationCategory', $classificationCategory);
    }

    /**
     * @return float Ποσό
     */
    public function getAmount(): float
    {
        return $this->get('amount');
    }

    /**
     * <h2>Ποσό</h2>
     *
     * @param float $amount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setAmount(float $amount): self
    {
        return $this->put('amount', $amount);
    }

    /**
     * @return int|null Αύξων αριθμός Χαρακτηρισμού
     */
    public function getId(): ?int
    {
        return $this->get('id');
    }

    /**
     * <h2>Αύξων αριθμός Χαρακτηρισμού</h2>
     *
     * <p>Το πεδίο id προσφέρεται για σειριακή αρίθμηση (1,2,3… κλπ) των χαρακτηρισμών
     * εντός μιας γραμμής.</p>
     *
     * @param int|null $id Αύξων αριθμός Χαρακτηρισμού
     */
    public function setId(?int $id): self
    {
        return $this->put('id', $id);
    }
}
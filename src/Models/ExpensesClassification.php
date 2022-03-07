<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;

/**
 * <p>Ο τύπος ExpensesClassification αποτελεί τη βασική δομή του Χαρακτηρισμού Εξόδων και εμπεριέχεται είτε</p>
 * <ul>
 * <li>σε κάθε γραμμή του παραστατικού ξεχωριστά (χαρακτηρισμός γραμμής)</li>
 * <li>στην περίληψη παραστατικού (άθροισμα χαρακτηρισμών ανά τύπο - κατηγορία)</li>
 * <li>στο αντικείμενο InvoiceExpensesClassification όταν οι χαρακτηρισμοί εσόδων υποβάλλονται ξεχωριστά μέσω της
 * κλήσης SendExpensesClassification (βλ παράγραφος 4.3.3)</li>
 * </ul>
 */
class ExpensesClassification extends Type
{
    /**
     * @return string|null Κωδικός Χαρακτηρισμού
     */
    public function getClassificationType(): ?string
    {
        return $this->get('classificationType');
    }

    /**
     * @param ExpenseClassificationType|string $classificationType Κωδικός Χαρακτηρισμού
     */
    public function setClassificationType(ExpenseClassificationType|string $classificationType): void
    {
        $this->put('classificationType', $classificationType);
    }

    /**
     * @return string|null Κατηγορία Χαρακτηρισμού
     */
    public function getClassificationCategory(): ?string
    {
        return $this->get('classificationCategory');
    }

    /**
     * @param ExpenseClassificationCategory|string $classificationCategory Κατηγορία Χαρακτηρισμού
     */
    public function setClassificationCategory(ExpenseClassificationCategory|string $classificationCategory): void
    {
        $this->put('classificationCategory', $classificationCategory);
    }

    /**
     * @return float|null Ποσό
     */
    public function getAmount(): ?float
    {
        return $this->get('amount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $amount Ποσό
     */
    public function setAmount(float $amount): void
    {
        $this->put('amount', $amount);
    }

    /**
     * @return int|null Αύξων αριθμός Χαρακτηρισμού
     */
    public function getId(): ?int
    {
        return $this->get('id');
    }

    /**
     * Το πεδίο id προσφέρεται για σειριακή αρίθμηση (1,2,3… κλπ) των χαρακτηρισμών εντός μιας γραμμής.
     *
     * @param int $id Αύξων αριθμός Χαρακτηρισμού
     */
    public function setId(int $id): void
    {
        $this->put('id', $id);
    }
}
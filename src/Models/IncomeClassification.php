<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * <p>Ο τύπος IncomeClassification αποτελεί τη βασική δομή του Χαρακτηρισμού Εσόδων και εμπεριέχεται είτε</p>
 * <ul>
 * <li>σε κάθε γραμμή του παραστατικού ξεχωριστά (χαρακτηρισμός γραμμής)</li>
 * <li>στην περίληψη παραστατικού (άθροισμα χαρακτηρισμών ανά τύπο - κατηγορία)</li>
 * <li>στο αντικείμενο InvoiceExpensesClassification όταν οι χαρακτηρισμοί εσόδων υποβάλλονται ξεχωριστά μέσω της
 * κλήσης SendIncomeClassification (βλ παράγραφος 4.3.2)</li>
 * </ul>
 */
class IncomeClassification extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'classificationType',
        'classificationCategory',
        'amount',
        'id'
    ];

    protected array $casts = [
        'classificationType'     => IncomeClassificationType::class,
        'classificationCategory' => IncomeClassificationCategory::class,
    ];

    /**
     * @return IncomeClassificationType|null Κωδικός Χαρακτηρισμού
     */
    public function getClassificationType(): ?IncomeClassificationType
    {
        return $this->get('classificationType');
    }

    /**
     * @param IncomeClassificationType|string|null $classificationType Κωδικός Χαρακτηρισμού
     */
    public function setClassificationType(IncomeClassificationType|string|null $classificationType): void
    {
        $this->set('classificationType', $classificationType);
    }

    /**
     * @return IncomeClassificationCategory|null Κατηγορία Χαρακτηρισμού
     */
    public function getClassificationCategory(): ?IncomeClassificationCategory
    {
        return $this->get('classificationCategory');
    }

    /**
     * @param IncomeClassificationCategory|string $classificationCategory Κατηγορία Χαρακτηρισμού
     */
    public function setClassificationCategory(IncomeClassificationCategory|string $classificationCategory): void
    {
        $this->set('classificationCategory', $classificationCategory);
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
        $this->set('amount', $amount);
    }

    /**
     * @return int|null Αύξων αριθμός Χαρακτηρισμού
     */
    public function getId(): ?int
    {
        return $this->get('id');
    }

    /**
     * Το πεδίο id προσφέρεται για σειριακή αρίθμηση των χαρακτηρισμών εντός μιας γραμμής.
     *
     * @param int $id Αύξων αριθμός Χαρακτηρισμού
     */
    public function setId(int $id): void
    {
        $this->set('id', $id);
    }
}
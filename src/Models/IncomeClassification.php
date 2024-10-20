<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Traits\HasFactory;
use InvalidArgumentException;

/**
 * Ο τύπος IncomeClassification αποτελεί τη βασική δομή του Χαρακτηρισμού Εσόδων και εμπεριέχεται είτε
       
 * - σε κάθε γραμμή του παραστατικού ξεχωριστά (χαρακτηρισμός γραμμής)
 * - στην περίληψη παραστατικού (άθροισμα χαρακτηρισμών ανά τύπο - κατηγορία)
 * - στο αντικείμενο InvoiceExpensesClassification όταν οι χαρακτηρισμοί εσόδων υποβάλλονται ξεχωριστά μέσω της
 *   κλήσης SendIncomeClassification (βλ παράγραφος 4.3.2)
        
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
     * @throws InvalidArgumentException
     */
    public function setClassificationType(IncomeClassificationType|string|null $classificationType): static
    {
        if (is_string($classificationType)) {
            $classificationType = IncomeClassificationType::tryFrom($classificationType);

            if (is_null($classificationType)) {
                throw new InvalidArgumentException('Invalid classification type provided.');
            }
        }

        return $this->set('classificationType', $classificationType);
    }

    /**
     * @return IncomeClassificationCategory|null Κατηγορία Χαρακτηρισμού
     */
    public function getClassificationCategory(): ?IncomeClassificationCategory
    {
        return $this->get('classificationCategory');
    }

    /**
     * @param IncomeClassificationCategory|string|null $classificationCategory Κατηγορία Χαρακτηρισμού
     * @throws InvalidArgumentException
     */
    public function setClassificationCategory(IncomeClassificationCategory|string|null $classificationCategory): static
    {
        if (is_string($classificationCategory)) {
            $classificationCategory = IncomeClassificationCategory::tryFrom($classificationCategory);

            if (is_null($classificationCategory)) {
                throw new InvalidArgumentException('Invalid classification category provided.');
            }
        }

        return $this->set('classificationCategory', $classificationCategory);
    }

    /**
     * @return float|null Ποσό
     */
    public function getAmount(): ?float
    {
        return $this->get('amount');
    }

    /**
           
                                             
     * Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
            
     *
     * @param float $amount Ποσό
     * @throws InvalidArgumentException
     */
    public function setAmount(float $amount): static
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('The amount cannot be negative.');
        }

                                                          
        $amount = round($amount, 2);

        return $this->set('amount', $amount);
    }

    /**
     * Προσθέτει το ποσό στην τρέχουσα τιμή.
     *
     * @param float $amount Το ποσό που θα προστεθεί
     * @throws InvalidArgumentException
     */
    public function addAmount(float $amount): static
    {
        $currentAmount = $this->getAmount() ?? 0.0;

        if ($amount < 0) {
            throw new InvalidArgumentException('The amount to add cannot be negative.');
        }

        return $this->set('amount', round($currentAmount + $amount, 2));
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
     * @throws InvalidArgumentException
     */
    public function setId(int $id): static
    {
        if ($id < 0) {
            throw new InvalidArgumentException('The ID cannot be negative.');
        }

        return $this->set('id', $id);
    }
}

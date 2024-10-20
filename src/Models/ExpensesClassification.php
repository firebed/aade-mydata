<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Traits\HasFactory;
use InvalidArgumentException;

/**
 * Ο τύπος ExpensesClassification αποτελεί τη βασική δομή του Χαρακτηρισμού Εξόδων και εμπεριέχεται είτε
       
 * - σε κάθε γραμμή του παραστατικού ξεχωριστά (χαρακτηρισμός γραμμής)
 * - στην περίληψη παραστατικού (άθροισμα χαρακτηρισμών ανά τύπο - κατηγορία)
 * - στο αντικείμενο InvoiceExpensesClassification όταν οι χαρακτηρισμοί εσόδων υποβάλλονται ξεχωριστά μέσω της
 *   κλήσης SendExpensesClassification (βλ παράγραφος 4.3.3)
        
 */
class ExpensesClassification extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'classificationType',
        'classificationCategory',
        'amount',
        'vatAmount',
        'vatCategory',
        'vatExemptionCategory',
        'id'
    ];

    protected array $casts = [
        'classificationType'     => ExpenseClassificationType::class,
        'classificationCategory' => ExpenseClassificationCategory::class,
        'vatCategory'            => VatCategory::class,
        'vatExemptionCategory'   => VatExemption::class,
    ];

    /**
     * @return ExpenseClassificationType|null Κωδικός Χαρακτηρισμού
     */
    public function getClassificationType(): ?ExpenseClassificationType
    {
        return $this->get('classificationType');
    }

    /**
     * Το πεδίο classificationCategory χρησιμοποιείται μόνο για τους χαρακτηρισμούς
     * εξόδων Ε3, αλλιώς αγνοείται.
     *
     * @param ExpenseClassificationType|string|null $classificationType Κωδικός Χαρακτηρισμού
     * @throws InvalidArgumentException
     */
    public function setClassificationType(ExpenseClassificationType|string|null $classificationType): static
    {
        if (is_string($classificationType)) {
            $classificationType = ExpenseClassificationType::tryFrom($classificationType);

            if (is_null($classificationType)) {
                throw new InvalidArgumentException('Invalid classification type provided.');
            }
        }

        return $this->set('classificationType', $classificationType);
    }

    /**
     * @return ExpenseClassificationCategory|null Κατηγορία Χαρακτηρισμού
     */
    public function getClassificationCategory(): ?ExpenseClassificationCategory
    {
        return $this->get('classificationCategory');
    }

    /**
     * @param ExpenseClassificationCategory|string|null $classificationCategory Κατηγορία Χαρακτηρισμού
     * @throws InvalidArgumentException
     */
    public function setClassificationCategory(ExpenseClassificationCategory|string|null $classificationCategory): static
    {
        if (is_string($classificationCategory)) {
            $classificationCategory = ExpenseClassificationCategory::tryFrom($classificationCategory);

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
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
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
     * @param float|null $amount Το ποσό που θα προστεθεί
     * @throws InvalidArgumentException
     */
    public function addAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        if ($amount < 0) {
            throw new InvalidArgumentException('The amount to add cannot be negative.');
        }

        $currentAmount = $this->getAmount() ?? 0.0;

        return $this->set('amount', round($currentAmount + $amount, 2));
    }

    /**
     * @return float|null Ποσό ΦΠΑ
                     
     */
    public function getVatAmount(): ?float
    {
        return $this->get('vatAmount');
    }

    /**
                                                                                                      
      
     * @param float|null $vatAmount Ποσό ΦΠΑ
     * @throws InvalidArgumentException
     */
    public function setVatAmount(?float $vatAmount): static
    {
        if ($vatAmount !== null && $vatAmount < 0) {
            throw new InvalidArgumentException('The VAT amount cannot be negative.');
        }

        $vatAmount = $vatAmount !== null ? round($vatAmount, 2) : null;

        return $this->set('vatAmount', $vatAmount);
    }

    /**
     * @param float|null $amount Το ποσό ΦΠΑ που θα προστεθεί
     * @throws InvalidArgumentException
     */
    public function addVatAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        if ($amount < 0) {
            throw new InvalidArgumentException('The VAT amount to add cannot be negative.');
        }

        $currentVatAmount = $this->getVatAmount() ?? 0.0;

        return $this->set('vatAmount', round($currentVatAmount + $amount, 2));
    }

    /**
     * @return VatCategory|null Κατηγορία ΦΠΑ
                     
     */
    public function getVatCategory(): ?VatCategory
    {
        return $this->get('vatCategory');
    }

    /**
     * Χρησιμοποιείτε μόνο για τους χαρακτηρισμούς εξόδων ΦΠΑ, διαφορετικά αγνοείται.
     *
     * @param  VatCategory|int|null  $vatCategory  Κατηγορία ΦΠΑ
     * @version 1.0.9
     * @throws InvalidArgumentException
     */
    public function setVatCategory(VatCategory|int|null $vatCategory): static
    {
        if (is_int($vatCategory)) {
            $vatCategory = VatCategory::tryFrom($vatCategory);

            if (is_null($vatCategory)) {
                throw new InvalidArgumentException('Invalid VAT category provided.');
            }
        }

        return $this->set('vatCategory', $vatCategory);
    }

    /**
     * @return VatExemption|null Κατηγορία Εξαίρεσης ΦΠΑ
     * @version 1.0.9                     
     */
    public function getVatExemptionCategory(): ?VatExemption
    {
        return $this->get('vatExemptionCategory');
    }

    /**
     * Χρησιμοποιείτε μόνο για τους χαρακτηρισμούς εξόδων ΦΠΑ, διαφορετικά αγνοείται.
     *
     * @param  VatExemption|int|null  $vatExemptionCategory  Κατηγορία Εξαίρεσης ΦΠΑ
     * @version 1.0.7
     * @throws InvalidArgumentException
     */
    public function setVatExemptionCategory(VatExemption|int|null $vatExemptionCategory): static
    {
        if (is_int($vatExemptionCategory)) {
            $vatExemptionCategory = VatExemption::tryFrom($vatExemptionCategory);

            if (is_null($vatExemptionCategory)) {
                throw new InvalidArgumentException('Invalid VAT exemption category provided.');
            }
        }

        return $this->set('vatExemptionCategory', $vatExemptionCategory);
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

<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Traits\HasFactory;

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
    
    /**
     * @return string|null Κωδικός Χαρακτηρισμού
     */
    public function getClassificationType(): ?string
    {
        return $this->get('classificationType');
    }

    /**
     * Το πεδίο classificationCategory χρησιμοποιείται μόνο για τους χαρακτηρισμούς
     * εξόδων Ε3, αλλιώς αγνοείται.
     *
     * @param ExpenseClassificationType|string $classificationType Κωδικός Χαρακτηρισμού
     */
    public function setClassificationType(ExpenseClassificationType|string $classificationType): void
    {
        $this->set('classificationType', $classificationType);
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
     * @return float|null Ποσό ΦΠΑ
     * @version 1.0.7
     */
    public function getVatAmount(): ?float
    {
        return $this->get('vatAmount');
    }

    /**
     * Χρησιμοποιείτε μόνο για τους χαρακτηρισμούς εξόδων ΦΠΑ, διαφορετικά αγνοείται.
     *
     * @param float|null $vatAmount Ποσό ΦΠΑ (Ελάχιστη τιμή 0, δεκαδικά 2)
     * @version 1.0.7
     */
    public function setVatAmount(?float $vatAmount): void
    {
        $this->set('vatAmount', $vatAmount);
    }

    /**
     * @return int|null Κατηγορία ΦΠΑ
     * @version 1.0.7
     */
    public function getVatCategory(): ?int
    {
        return $this->get('vatCategory');
    }

    /**
     * Χρησιμοποιείτε μόνο για τους χαρακτηρισμούς εξόδων ΦΠΑ, διαφορετικά αγνοείται.
     *
     * @param VatCategory|int|null $vatCategory Κατηγορία ΦΠΑ
     * @version 1.0.7
     */
    public function setVatCategory(VatCategory|int|null $vatCategory): void
    {
        $this->set('vatCategory', $vatCategory);
    }

    /**
     * @return int|null Κατηγορία Εξαίρεσης ΦΠΑ
     * @version 1.0.7
     */
    public function getVatExemptionCategory(): ?int
    {
        return $this->get('vatExemptionCategory');
    }

    /**
     * Χρησιμοποιείτε μόνο για τους χαρακτηρισμούς εξόδων ΦΠΑ, διαφορετικά αγνοείται.
     *
     * @param VatExemption|int|null $vatExemptionCategory Κατηγορία Εξαίρεσης ΦΠΑ
     * @version 1.0.7
     */
    public function setVatExemptionCategory(VatExemption|int|null $vatExemptionCategory): void
    {
        $this->set('vatExemptionCategory', $vatExemptionCategory);
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
        $this->set('id', $id);
    }
}
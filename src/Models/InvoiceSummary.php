<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Traits\HasFactory;

class InvoiceSummary extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'totalNetValue',
        'totalVatAmount',
        'totalWithheldAmount',
        'totalFeesAmount',
        'totalStampDutyAmount',
        'totalOtherTaxesAmount',
        'totalDeductionsAmount',
        'totalGrossValue',
        'incomeClassification',
        'expensesClassification'
    ];

    public array $casts = [
        'incomeClassification'   => IncomeClassification::class,
        'expensesClassification' => ExpensesClassification::class,
    ];

    /**
     * @return float|null Σύνολο Καθαρής Αξίας
     */
    public function getTotalNetValue(): ?float
    {
        return $this->get('totalNetValue');
    }

    /**
     * Το σύνολο καθαρής αξίας ($totalNetValue) είναι είτε το άθροισμα των αντίστοιχων
     * φόρων των γραμμών του παραστατικού, είτε των αντίστοιχων φόρων που περιέχονται
     * στο στοιχείο taxesTotals.
     *
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalNetValue Σύνολο Καθαρής Αξίας
     */
    public function setTotalNetValue(float $totalNetValue): void
    {
        $this->set('totalNetValue', $totalNetValue);
    }

    /**
     * @return float|null Σύνολο ΦΠΑ
     */
    public function getTotalVatAmount(): ?float
    {
        return $this->get('totalVatAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalVatAmount Σύνολο ΦΠΑ
     */
    public function setTotalVatAmount(float $totalVatAmount): void
    {
        $this->set('totalVatAmount', $totalVatAmount);
    }

    /**
     * @return float|null Σύνολο Παρακρατήσεων Φόρων
     */
    public function getTotalWithheldAmount(): ?float
    {
        return $this->get('totalWithheldAmount');
    }

    /**
     * Το σύνολο παρακρατήσεων φόρων ($totalWithheldAmount) είναι είτε το
     * άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού, είτε
     * των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.
     *
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalWithheldAmount Σύνολο Παρακρατήσεων Φόρων
     */
    public function setTotalWithheldAmount(float $totalWithheldAmount): void
    {
        $this->set('totalWithheldAmount', $totalWithheldAmount);
    }

    /**
     * @return float|null Σύνολο Τελών
     */
    public function getTotalFeesAmount(): ?float
    {
        return $this->get('totalFeesAmount');
    }

    /**
     * Το σύνολο τελών ($totalFeesAmount) είναι είτε το άθροισμα των
     * αντίστοιχων φόρων των γραμμών του παραστατικού, είτε των
     * αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.
     *
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalFeesAmount Σύνολο Τελών
     */
    public function setTotalFeesAmount(float $totalFeesAmount): void
    {
        $this->set('totalFeesAmount', $totalFeesAmount);
    }

    /**
     * @return float|null Σύνολο Χαρτοσήμου
     */
    public function getTotalStampDutyAmount(): ?float
    {
        return $this->get('totalStampDutyAmount');
    }

    /**
     * Το σύνολο χαρτοσήμου ($totalStampDutyAmount) είναι είτε το
     * άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.
     *
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalStampDutyAmount Σύνολο Χαρτοσήμου
     */
    public function setTotalStampDutyAmount(float $totalStampDutyAmount): void
    {
        $this->set('totalStampDutyAmount', $totalStampDutyAmount);
    }

    /**
     * @return float|null Σύνολο Λοιπών Φόρων
     */
    public function getTotalOtherTaxesAmount(): ?float
    {
        return $this->get('totalOtherTaxesAmount');
    }

    /**
     * Το σύνολο λοιπών φόρων ($totalOtherTaxesAmount) είναι είτε το
     * άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.
     *
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalOtherTaxesAmount Σύνολο Λοιπών Φόρων
     */
    public function setTotalOtherTaxesAmount(float $totalOtherTaxesAmount): void
    {
        $this->set('totalOtherTaxesAmount', $totalOtherTaxesAmount);
    }

    /**
     * @return float|null Σύνολο Κρατήσεων
     */
    public function getTotalDeductionsAmount(): ?float
    {
        return $this->get('totalDeductionsAmount');
    }

    /**
     * Το σύνολο κρατήσεων ($totalDeductionsAmount) είναι είτε το άθροισμα
     * των αντίστοιχων φόρων των γραμμών του παραστατικού, είτε
     * των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.
     *
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalDeductionsAmount Σύνολο Κρατήσεων
     */
    public function setTotalDeductionsAmount(float $totalDeductionsAmount): void
    {
        $this->set('totalDeductionsAmount', $totalDeductionsAmount);
    }

    /**
     * @return float|null Συνολική Αξία
     */
    public function getTotalGrossValue(): ?float
    {
        return $this->get('totalGrossValue');
    }

    /**
     * Η συνολική αξία ($totalGrossValue) είναι είτε το άθροισμα των
     * αντίστοιχων φόρων των γραμμών του παραστατικού, είτε των
     * αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.
     *
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $totalGrossValue Συνολική Αξία
     */
    public function setTotalGrossValue(float $totalGrossValue): void
    {
        $this->set('totalGrossValue', $totalGrossValue);
    }

    /**
     * @return IncomeClassification[]|null Χαρακτηρισμοί Εσόδων
     */
    public function getIncomeClassifications(): ?array
    {
        return $this->get('incomeClassification');
    }

    /**
     * <h2>Χαρακτηρισμοί Εσόδων</h2>
     *
     * <p>Το incomeClassification περιέχει το άθροισμα για κάθε συνδυασμό όλων των πεδίων
     * incomeClassificationCategory που εντοπίζονται στις γραμμές του παραστατικού.</p>
     *
     * @param IncomeClassification|IncomeClassificationType $incomeClassification Χαρακτηρισμοί Εσόδων
     * @param IncomeClassificationCategory|null $classificationCategory
     * @param float|null $classificationAmount
     */
    public function addIncomeClassification(IncomeClassification|IncomeClassificationType $incomeClassification, IncomeClassificationCategory $classificationCategory = null, float $classificationAmount = null): void
    {
        if ($incomeClassification instanceof IncomeClassification) {
            $this->push('incomeClassification', $incomeClassification);
        } else {
            $classification = new IncomeClassification();
            $classification->setClassificationType($incomeClassification);
            $classification->setClassificationCategory($classificationCategory);
            $classification->setAmount($classificationAmount);
            $this->addIncomeClassification($classification);
        }
    }

    /**
     * @return ExpensesClassification[]|null Χαρακτηρισμοί Εξόδων
     */
    public function getExpensesClassifications(): ?array
    {
        return $this->get('expensesClassification');
    }

    /**
     * <h2>Χαρακτηρισμοί Εξόδων</h2>
     *
     * <p>Το expensesClassification περιέχει το άθροισμα για κάθε συνδυασμό όλων των πεδίων
     * expensesClassificationCategory που εντοπίζονται στις γραμμές του παραστατικού.</p>
     *
     * @param ExpensesClassification|ExpenseClassificationType $expenseClassification
     * @param ExpenseClassificationCategory|null $expenseClassificationCategory
     * @param float|null $classificationAmount
     */
    public function addExpensesClassification(ExpensesClassification|ExpenseClassificationType $expenseClassification, ExpenseClassificationCategory $expenseClassificationCategory = null, float $classificationAmount = null): void
    {
        if ($expenseClassification instanceof ExpensesClassification) {
            $this->push('expensesClassification', $expenseClassification);
        } else {
            $classification = new ExpensesClassification();
            $classification->setClassificationType($expenseClassification);
            $classification->setClassificationCategory($expenseClassificationCategory);
            $classification->setAmount($classificationAmount);
            $this->addExpensesClassification($classification);
        }
    }

    public function set($key, $value): void
    {
        if ($key === 'expensesClassification' || $key === 'incomeClassification') {
            if (is_array($value)) {
                parent::set($key, $value);
            } else {
                $this->push($key, $value);
            }
            return;
        }

        parent::set($key, $value);
    }
}
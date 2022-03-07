<?php

namespace Firebed\AadeMyData\Models;

class InvoiceSummary extends Type
{
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
        $this->put('totalNetValue', $totalNetValue);
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
        $this->put('totalVatAmount', $totalVatAmount);
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
        $this->put('totalWithheldAmount', $totalWithheldAmount);
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
        $this->put('totalFeesAmount', $totalFeesAmount);
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
        $this->put('totalStampDutyAmount', $totalStampDutyAmount);
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
        $this->put('totalOtherTaxesAmount', $totalOtherTaxesAmount);
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
        $this->put('totalDeductionsAmount', $totalDeductionsAmount);
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
        $this->put('totalGrossValue', $totalGrossValue);
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
     * @param IncomeClassification $incomeClassification Χαρακτηρισμοί Εσόδων
     */
    public function addIncomeClassification(IncomeClassification $incomeClassification): void
    {
        $this->push('incomeClassification', $incomeClassification);
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
     * @param ExpensesClassification $expensesClassification Χαρακτηρισμοί Εξόδων
     */
    public function addExpensesClassification(ExpensesClassification $expensesClassification): void
    {
        $this->push('expensesClassification', $expensesClassification);
    }
    
    public function put($key, $value): void
    {
        if ($key === 'expensesClassification' || $key === 'incomeClassification') {
            $this->push($key, $value);
            return;
        }
    
        parent::put($key, $value);
    }
}
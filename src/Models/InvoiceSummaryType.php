<?php

namespace Firebed\AadeMyData\Models;

class InvoiceSummaryType extends Type
{
    /**
     * @return float Σύνολο Καθαρής Αξίας
     */
    public function getTotalNetValue(): float
    {
        return $this->get('totalNetValue');
    }

    /**
     * <h2>Σύνολο Καθαρής Αξίας</h2>
     *
     * <p>Είναι είτε το άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.</p>
     *
     * @param float $totalNetValue Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalNetValue(float $totalNetValue): self
    {
        return $this->put('totalNetValue', $totalNetValue);
    }

    /**
     * @return float Σύνολο ΦΠΑ
     */
    public function getTotalVatAmount(): float
    {
        return $this->get('totalVatAmount');
    }

    /**
     * <h2>Σύνολο ΦΠΑ</h2>
     *
     * @param float $totalVatAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalVatAmount(float $totalVatAmount): self
    {
        return $this->put('totalVatAmount', $totalVatAmount);
    }

    /**
     * @return float Σύνολο Παρακρατήσεων Φόρων
     */
    public function getTotalWithheldAmount(): float
    {
        return $this->get('totalWithheldAmount');
    }

    /**
     * <h2>Σύνολο Παρακρατήσεων Φόρων</h2>
     *
     * <p>Είναι είτε το άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.</p>
     *
     * @param float $totalWithheldAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalWithheldAmount(float $totalWithheldAmount): self
    {
        return $this->put('totalWithheldAmount', $totalWithheldAmount);
    }

    /**
     * @return float Σύνολο Τελών
     */
    public function getTotalFeesAmount(): float
    {
        return $this->get('totalFeesAmount');
    }

    /**
     * <h2>Σύνολο Τελών</h2>
     *
     * <p>Είναι είτε το άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.</p>
     *
     * @param float $totalFeesAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalFeesAmount(float $totalFeesAmount): self
    {
        return $this->put('totalFeesAmount', $totalFeesAmount);
    }

    /**
     * @return float Σύνολο Χαρτοσήμου
     */
    public function getTotalStampDutyAmount(): float
    {
        return $this->get('totalStampDutyAmount');
    }

    /**
     * <h2>Σύνολο Χαρτοσήμου</h2>
     *
     * <p>Είναι είτε το άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.</p>
     *
     * @param float $totalStampDutyAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalStampDutyAmount(float $totalStampDutyAmount): self
    {
        return $this->put('totalStampDutyAmount', $totalStampDutyAmount);
    }

    /**
     * @return float Σύνολο Λοιπών Φόρων
     */
    public function getTotalOtherTaxesAmount(): float
    {
        return $this->get('totalOtherTaxesAmount');
    }

    /**
     * <h2>Σύνολο Λοιπών Φόρων</h2>
     *
     * <p>Είναι είτε το άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.</p>
     *
     * @param float $totalOtherTaxesAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalOtherTaxesAmount(float $totalOtherTaxesAmount): self
    {
        return $this->put('totalOtherTaxesAmount', $totalOtherTaxesAmount);
    }

    /**
     * @return float Σύνολο Κρατήσεων
     */
    public function getTotalDeductionsAmount(): float
    {
        return $this->get('totalDeductionsAmount');
    }

    /**
     * <h2>Σύνολο Κρατήσεων</h2>
     *
     * <p>Είναι είτε το άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.</p>
     *
     * @param float $totalDeductionsAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalDeductionsAmount(float $totalDeductionsAmount): self
    {
        return $this->put('totalDeductionsAmount', $totalDeductionsAmount);
    }

    /**
     * @return float Συνολική Αξία
     */
    public function getTotalGrossValue(): float
    {
        return $this->get('totalGrossValue');
    }

    /**
     * <h2>Συνολική Αξία</h2>
     *
     * <p>Είναι είτε το άθροισμα των αντίστοιχων φόρων των γραμμών του παραστατικού,
     * είτε των αντίστοιχων φόρων που περιέχονται στο στοιχείο taxesTotals.</p>
     *
     * @param float $totalGrossValue Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTotalGrossValue(float $totalGrossValue): self
    {
        return $this->put('totalGrossValue', $totalGrossValue);
    }

    /**
     * @return array|null Χαρακτηρισμοί Εσόδων
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
     * @param IncomeClassificationType $incomeClassification Χαρακτηρισμοί Εσόδων
     */
    public function addIncomeClassification(IncomeClassificationType $incomeClassification): self
    {
        $entries = $this->get('incomeClassification', []);
        $entries[] = $incomeClassification;
        return $this->put('incomeClassification', $entries);
    }

    /**
     * @return array|null Χαρακτηρισμοί Εξόδων
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
     * @param ExpensesClassificationType $expensesClassification Χαρακτηρισμοί Εξόδων
     */
    public function addExpensesClassification(ExpensesClassificationType $expensesClassification): self
    {
        $entries = $this->get('expensesClassification', []);
        $entries[] = $expensesClassification;
        return $this->put('expensesClassification', $entries);
    }
}
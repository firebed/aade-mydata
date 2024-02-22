<?php

namespace Firebed\AadeMyData\Models;

class InvoicesIncomeClassificationDetail extends Type
{
    protected array $casts = [
        'incomeClassificationDetailData' => IncomeClassification::class,
    ];
    
    /**
     * @return int|null Αριθμός Γραμμής
     */
    public function getLineNumber(): ?int
    {
        return $this->get('lineNumber');
    }

    /**
     * Αναφέρεται στον αντίστοιχο αριθμό γραμμής του αρχικού παραστατικού με Μοναδικός Αριθμός Καταχώρησης αυτό του πεδίου mark.
     * 
     * @param int $lineNumber Αριθμός Γραμμής
     */
    public function setLineNumber(int $lineNumber): void
    {
        $this->set('lineNumber', $lineNumber);
    }

    /**
     * @return IncomeClassification[]|null Λίστα Χαρακτηρισμών Εσόδων
     */
    public function getIncomeClassificationDetailData(): ?array
    {
        return $this->get('incomeClassificationDetailData');
    }

    /**
     * @param IncomeClassification[] $incomeClassificationDetailData Λίστα Χαρακτηρισμών Εσόδων
     */
    public function setIncomeClassificationDetailData(array $incomeClassificationDetailData): void
    {
        $this->set('incomeClassificationDetailData', $incomeClassificationDetailData);
    }

}
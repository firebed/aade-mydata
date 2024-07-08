<?php

namespace Firebed\AadeMyData\Models;

class InvoicesIncomeClassificationDetail extends Type
{
    protected array $expectedOrder = [
        'lineNumber',
        'incomeClassificationDetailData',
    ];
    
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
    public function setLineNumber(int $lineNumber): static
    {
        return $this->set('lineNumber', $lineNumber);
    }

    /**
     * @return IncomeClassification[]|null Λίστα Χαρακτηρισμών Εσόδων
     */
    public function getClassificationDetails(): ?array
    {
        return $this->get('incomeClassificationDetailData');
    }

    public function addClassificationDetail(IncomeClassification $classification): static
    {
        return $this->push('incomeClassificationDetailData', $classification);
    }

    /**
     * @param IncomeClassification[] $classificationDetails Λίστα Χαρακτηρισμών Εσόδων
     */
    public function setClassificationDetails(array $classificationDetails): static
    {
        return $this->set('incomeClassificationDetailData', $classificationDetails);
    }

}
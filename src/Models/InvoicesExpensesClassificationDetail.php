<?php

namespace Firebed\AadeMyData\Models;

class InvoicesExpensesClassificationDetail extends Type
{
    protected array $casts = [
        'expensesClassificationDetailData' => ExpensesClassification::class,
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
     * @return ExpensesClassification[]|null
     */
    public function getExpensesClassificationDetailData(): ?array
    {
        return $this->get('expensesClassificationDetailData');
    }

    /**
     * Κάθε στοιχείο invoicesExpensesClassificationDetails περιέχει ένα lineNumber και
     * μια λίστα στοιχείων expensesClassificationDetailData.
     * 
     * @param ExpensesClassification[] $expensesClassificationDetailData
     */
    public function setExpensesClassificationDetailData(array $expensesClassificationDetailData): static
    {
        return $this->set('expensesClassificationDetailData', $expensesClassificationDetailData);
    }
}
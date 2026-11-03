<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

class InvoicesExpensesClassificationDetail extends Model
{
    protected array $expectedOrder = [
        'lineNumber',
        'expensesClassificationDetailData',
    ];

    protected function casts(): array
    {
        return [
            'expensesClassificationDetailData' => Arr::of(ExpensesClassification::class),
        ];
    }

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
     * @param  int  $lineNumber  Αριθμός Γραμμής
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

    public function addExpensesClassificationDetailData(ExpensesClassification $classification): static
    {
        return $this->push('expensesClassificationDetailData', $classification);
    }

    /**
     * @param  ExpensesClassification[]  $expensesClassificationDetailData
     */
    public function setExpensesClassificationDetailData(array $expensesClassificationDetailData): static
    {
        return $this->set('expensesClassificationDetailData', $expensesClassificationDetailData);
    }
}

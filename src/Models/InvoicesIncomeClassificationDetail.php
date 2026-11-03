<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

class InvoicesIncomeClassificationDetail extends Model
{
    protected array $expectedOrder = [
        'lineNumber',
        'incomeClassificationDetailData',
    ];

    protected function casts(): array
    {
        return [
            'incomeClassificationDetailData' => Arr::of(IncomeClassification::class),
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
     * @return IncomeClassification[]|null Λίστα Χαρακτηρισμών Εσόδων
     */
    public function getIncomeClassificationDetailData(): ?array
    {
        return $this->get('incomeClassificationDetailData');
    }

    public function addIncomeClassificationDetailData(IncomeClassification $classification): static
    {
        return $this->push('incomeClassificationDetailData', $classification);
    }

    /**
     * @param  IncomeClassification[]  $classificationDetails  Λίστα Χαρακτηρισμών Εσόδων
     */
    public function setIncomeClassificationDetailData(array $classificationDetails): static
    {
        return $this->set('incomeClassificationDetailData', $classificationDetails);
    }
}

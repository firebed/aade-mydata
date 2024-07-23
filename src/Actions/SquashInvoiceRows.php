<?php

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceDetails;

class SquashInvoiceRows
{
    /**
     * @var InvoiceDetails[] Variable to store rows with RecType.
     */
    private array $rowsWithRecType = [];

    /**
     * @var InvoiceDetails[] Variable to store squashed rows.
     */
    private array $squashedRows = [];

    /**
     * @var array Variable to store squashed income classifications.
     */
    private array $squashedIcls = [];

    /**
     * @var array Variable to store squashed expenses classifications.
     */
    private array $squashedEcls = [];

    private array $options = [];

    /**
     * Groups similar rows and returns a new array with the rows summed.
     *
     * @param  InvoiceDetails[]|null  $invoiceRows  An array of invoice rows.
     * @param  array  $options  Additional options.
     * @return array|null An array of squashed invoice rows.
     */
    public function handle(?array $invoiceRows, array $options = []): ?array
    {
        if ($invoiceRows === null) {
            return null;
        }

        $this->options = $options;

        return $this->squashInvoiceRows($invoiceRows);
    }

    /**
     * Squashes invoice rows by combining rows with identical categories
     * and summing their values.
     *
     * @param  InvoiceDetails[]  $invoiceRows
     * @return array
     */
    private function squashInvoiceRows(array $invoiceRows): array
    {
        foreach ($invoiceRows as $row) {
            if ($row->getRecType() !== null) {
                $this->rowsWithRecType[] = $row;
                continue;
            }

            $rowKey = $this->generateRowKey($row);
            $squashedRow = $this->squashedRows[$rowKey] ??= new InvoiceDetails($this->extractConcreteData($row));

            $this->aggregateRowData($squashedRow, $row);
            $this->aggregateIncomeClassifications($rowKey, $row->getIncomeClassification());
            $this->aggregateExpenseClassifications($rowKey, $row->getExpensesClassification());
        }

        return $this->mergeAndRoundResults();
    }

    /**
     * Generates a unique key for each row based on its categories.
     *
     * @param  InvoiceDetails  $row
     * @return string
     */
    private function generateRowKey(InvoiceDetails $row): string
    {
        return implode('-', [
            $row->getVatCategory()->value ?? '',
            $row->getVatExemptionCategory()->value ?? '',
            $row->getWithheldPercentCategory()->value ?? '',
            $row->getFeesPercentCategory()->value ?? '',
            $row->getOtherTaxesPercentCategory()->value ?? '',
            $row->getStampDutyPercentCategory()->value ?? '',
            $row->getNotVAT195() ? '1' : '',
        ]);
    }

    /**
     * Extracts data from the row for initializing a new squashed row.
     *
     * @param  InvoiceDetails  $row
     * @return array
     */
    private function extractConcreteData(InvoiceDetails $row): array
    {
        return [
            'vatCategory' => $row->getVatCategory(),
            'vatExemptionCategory' => $row->getVatExemptionCategory(),
            'withheldPercentCategory' => $row->getWithheldPercentCategory(),
            'feesPercentCategory' => $row->getFeesPercentCategory(),
            'otherTaxesPercentCategory' => $row->getOtherTaxesPercentCategory(),
            'stampDutyPercentCategory' => $row->getStampDutyPercentCategory(),
            'notVAT195' => $row->getNotVAT195(),
        ];
    }

    /**
     * Aggregates data from one row into another.
     *
     * @param  InvoiceDetails  $target
     * @param  InvoiceDetails  $source
     * @return void
     */
    private function aggregateRowData(InvoiceDetails $target, InvoiceDetails $source): void
    {
        $target->addNetValue($source->getNetValue());
        $target->addVatAmount($source->getVatAmount());
        $target->addWithheldAmount($source->getWithheldAmount());
        $target->addFeesAmount($source->getFeesAmount());
        $target->addOtherTaxesAmount($source->getOtherTaxesAmount());
        $target->addStampDutyAmount($source->getStampDutyAmount());
        $target->addDeductionsAmount($source->getDeductionsAmount());
    }

    /**
     * Aggregates income classifications for a given row key.
     *
     * @param  string  $rowKey
     * @param  IncomeClassification[]|null  $classifications
     * @return void
     */
    private function aggregateIncomeClassifications(string $rowKey, ?array $classifications): void
    {
        if (empty($classifications)) {
            return;
        }

        foreach ($classifications as $classification) {
            $iclsKey = implode('-', [
                ($classification->getClassificationCategory()->value ?? ''),
                ($classification->getClassificationType()->value ?? ''),
            ]);

            $squashedIcls = $this->squashedIcls[$rowKey][$iclsKey] ??= new IncomeClassification([
                'classificationCategory' => $classification->getClassificationCategory(),
                'classificationType' => $classification->getClassificationType(),
            ]);

            $squashedIcls->addAmount($classification->getAmount());
        }
    }

    /**
     * Aggregates expense classifications for a given row key.
     *
     * @param  string  $rowKey
     * @param  ExpensesClassification[]|null  $classifications
     * @return void
     */
    private function aggregateExpenseClassifications(string $rowKey, ?array $classifications): void
    {
        if (empty($classifications)) {
            return;
        }

        foreach ($classifications as $classification) {
            $eclsKey = implode('-', [
                ($classification->getClassificationCategory()->value ?? ''),
                ($classification->getClassificationType()->value ?? ''),
                ($classification->getVatCategory()->value ?? ''),
                ($classification->getVatExemptionCategory()->value ?? ''),
            ]);

            $squashedEcls = $this->squashedEcls[$rowKey][$eclsKey] ??= new ExpensesClassification([
                'classificationCategory' => $classification->getClassificationCategory(),
                'classificationType' => $classification->getClassificationType(),
                'vatCategory' => $classification->getVatCategory(),
                'vatExemptionCategory' => $classification->getVatExemptionCategory(),
            ]);

            $squashedEcls->addAmount($classification->getAmount());
            $squashedEcls->addVatAmount($classification->getVatAmount());
        }
    }

    /**
     * Merges results from squashed rows, classifications, and rows with RecType.
     *
     * @return array
     */
    private function mergeAndRoundResults(): array
    {
        $lineNumber = 1;
        
        foreach ($this->squashedRows as $key => $row) {
            $clsLineNumber = 1;

            if (isset($this->squashedIcls[$key])) {
                $row->setIncomeClassification($this->mapClassifications($this->squashedIcls[$key], $clsLineNumber));
            }

            if (isset($this->squashedEcls[$key])) {
                $row->setExpensesClassification($this->mapClassifications($this->squashedEcls[$key], $clsLineNumber));
            }

            $row->setLineNumber($lineNumber++);
            
            $this->roundRow($row);
            $this->roundClassifications($row);
            $this->adjustClassificationAmount($row);
        }
        
        foreach ($this->rowsWithRecType as $row) {
            $row->setLineNumber($lineNumber++);
        }

        return array_merge(array_values($this->squashedRows), $this->rowsWithRecType);
    }

    /**
     * @param  ExpensesClassification[]|IncomeClassification[]  $classifications
     * @param  int  $lineNumber
     * @return array
     */
    private function mapClassifications(array $classifications, int &$lineNumber = 1): array
    {
        // If clsLineNumber option is set to true, we will add a line number to each classification.
        if (isset($this->options['clsLineNumber']) && $this->options['clsLineNumber'] === true) {
            return array_map(function ($cls) use (&$lineNumber) {
                $cls->setId($lineNumber++);
                return $cls;
            }, array_values($classifications));
        }

        return array_values($classifications);
    }

    /**
     * Rounds the values of a row.
     *
     * @param  InvoiceDetails  $row
     * @return void
     */
    private function roundRow(InvoiceDetails $row): void
    {
        if ($row->getNetValue() !== null) {
            $row->setNetValue(round($row->getNetValue(), 2));
        }

        if ($row->getVatAmount() !== null) {
            $row->setVatAmount(round($row->getVatAmount(), 2));
        }

        if ($row->getWithheldAmount() !== null) {
            $row->setWithheldAmount(round($row->getWithheldAmount(), 2));
        }

        if ($row->getFeesAmount() !== null) {
            $row->setFeesAmount(round($row->getFeesAmount(), 2));
        }

        if ($row->getOtherTaxesAmount() !== null) {
            $row->setOtherTaxesAmount(round($row->getOtherTaxesAmount(), 2));
        }

        if ($row->getStampDutyAmount() !== null) {
            $row->setStampDutyAmount(round($row->getStampDutyAmount(), 2));
        }

        if ($row->getDeductionsAmount() !== null) {
            $row->setDeductionsAmount(round($row->getDeductionsAmount(), 2));
        }
    }

    private function roundClassifications(InvoiceDetails $row): void
    {
        if ($row->getIncomeClassification()) {
            foreach ($row->getIncomeClassification() as $classification) {
                if ($classification->getAmount() !== null) {
                    $classification->setAmount(round($classification->getAmount(), 2));
                }
            }
        }

        if ($row->getExpensesClassification()) {
            foreach ($row->getExpensesClassification() as $classification) {
                if ($classification->getAmount() !== null) {
                    $classification->setAmount(round($classification->getAmount(), 2));
                }

                if ($classification->getVatAmount() !== null) {
                    $classification->setVatAmount(round($classification->getVatAmount(), 2));
                }
            }
        }
    }

    private function adjustClassificationAmount(InvoiceDetails $row): void
    {
        $incomeClassification = $row->getIncomeClassification() ?? [];
        $expensesClassification = $row->getExpensesClassification() ?? [];

        $classifications = array_merge($incomeClassification, $expensesClassification);

        $classificationSum = array_sum(array_map(fn($item) => $item->getAmount(), $classifications));
        $diff = round($row->getNetValue() - $classificationSum, 2);

        if ($diff != 0) {
            end($classifications);
            $lastKey = key($classifications);
            if ($lastKey !== null) {
                $classifications[$lastKey]->addAmount($diff);
            }
        }
    }
}

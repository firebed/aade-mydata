<?php

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceDetails;

class SquashInvoiceRows
{
    private array $rowsWithRecType = [];

    /**
     * @var InvoiceDetails[]
     */
    private array $squashedRows    = [];
    
    private array $squashedIcls    = [];
    
    private array $squashedEcls    = [];

    /**
     * Groups similar rows and returns a new array with the rows summed.
     *
     * @param  InvoiceDetails[]|null  $invoiceRows  An array of invoice rows.
     * @return array|null An array of squashed invoice rows.
     */
    public function handle(?array $invoiceRows): ?array
    {
        if ($invoiceRows === null) {
            return null;
        }

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

        return $this->mergeResults();
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
            $row->getStampDutyPercentCategory()->value ?? ''
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
        $target->addWithheldAmount($source->getWithheldAmount() ?? 0);
        $target->addFeesAmount($source->getFeesAmount() ?? 0);
        $target->addOtherTaxesAmount($source->getOtherTaxesAmount() ?? 0);
        $target->addStampDutyAmount($source->getStampDutyAmount() ?? 0);
        $target->addDeductionsAmount($source->getDeductionsAmount() ?? 0);
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
    private function mergeResults(): array
    {
        foreach ($this->squashedRows as $key => $row) {            
            if (isset($this->squashedIcls[$key])) {
                $row->setIncomeClassification(array_values($this->squashedIcls[$key]));
            }

            if (isset($this->squashedEcls[$key])) {
                $row->setExpensesClassification(array_values($this->squashedEcls[$key]));
            }

            $row->roundAmounts();
        }

        return array_merge(array_values($this->squashedRows), $this->rowsWithRecType);
    }
}

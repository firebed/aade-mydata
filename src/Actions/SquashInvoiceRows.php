<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Actions;

use BackedEnum;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceDetails;

class SquashInvoiceRows
{
    /**
     * The attributes rows are grouped by. A squashed row takes them from the first row of its
     * group; every other attribute is summed or left out.
     */
    private const array GROUP_BY = [
        'vatCategory',
        'vatExemptionCategory',
        'withheldPercentCategory',
        'feesPercentCategory',
        'otherTaxesPercentCategory',
        'stampDutyPercentCategory',
        'notVAT195',
        'discountOption',
        'invoiceDetailType',
    ];

    /**
     * @var InvoiceDetails[] Variable to store rows with RecType.
     */
    private array $rowsWithRecType = [];

    /**
     * @var InvoiceDetails[] Variable to store squashed rows.
     */
    private array $squashedRows = [];

    /**
     * @var array<int, array<string, IncomeClassification>> Variable to store squashed income classifications, per group.
     */
    private array $squashedIcls = [];

    /**
     * @var array<int, array<string, ExpensesClassification>> Variable to store squashed expenses classifications, per group.
     */
    private array $squashedEcls = [];

    /** @var array{clsLineNumber?: bool, vatAmountTolerance?: float|null} */
    private array $options = [];

    /**
     * Groups similar rows and returns a new array with the rows summed.
     *
     * @param  InvoiceDetails[]|null  $invoiceRows  An array of invoice rows.
     * @param  array{clsLineNumber?: bool, vatAmountTolerance?: float|null}  $options  Additional options.
     * @return InvoiceDetails[]|null An array of squashed invoice rows.
     */
    public function handle(?array $invoiceRows, array $options = []): ?array
    {
        if ($invoiceRows === null) {
            return null;
        }

        $this->rowsWithRecType = [];
        $this->squashedRows = [];
        $this->squashedIcls = [];
        $this->squashedEcls = [];
        $this->options = $options;

        foreach ($this->groups($invoiceRows, $options) as $index => $group) {
            if ($group[0]->getRecType() !== null) {
                // Cloned, since the output rows are renumbered and the caller keeps the originals.
                $this->rowsWithRecType[] = clone $group[0];

                continue;
            }

            $squashedRow = $this->squashedRows[$index] = new InvoiceDetails($this->extractConcreteData($group[0]));

            foreach ($group as $row) {
                $this->aggregateRowData($squashedRow, $row);
                $this->aggregateIncomeClassifications($index, $row->getIncomeClassification());
                $this->aggregateExpenseClassifications($index, $row->getExpensesClassification());
            }
        }

        return $this->mergeAndRoundResults();
    }

    /**
     * Returns the rows that are squashed into the same row, without summing them,
     * one group per squashed row in the order handle() returns them: the squashed
     * rows first, then each row with a recType, which is not squashed and forms a
     * group of its own.
     *
     * @param  InvoiceDetails[]  $invoiceRows  An array of invoice rows.
     * @param  array{clsLineNumber?: bool, vatAmountTolerance?: float|null}  $options  The squashing options; only 'vatAmountTolerance' affects grouping.
     * @return InvoiceDetails[][]
     */
    public function groups(array $invoiceRows, array $options = []): array
    {
        $tolerance = isset($options['vatAmountTolerance']) ? (float) $options['vatAmountTolerance'] : null;
        $groups = [];
        $groupTotals = [];
        $rowsWithRecType = [];

        foreach ($invoiceRows as $row) {
            if ($row->getRecType() !== null) {
                $rowsWithRecType[] = [$row];

                continue;
            }

            if ($tolerance === null) {
                $groups[$this->generateRowKey($row)][] = $row;

                continue;
            }

            $groupKey = $this->toleratedGroupKey($row, $tolerance, $groupTotals);
            $groups[$groupKey][] = $row;

            [$netValue, $vatAmount] = $groupTotals[$groupKey] ?? [0.0, 0.0];
            $groupTotals[$groupKey] = [$netValue + ($row->getNetValue() ?? 0), $vatAmount + ($row->getVatAmount() ?? 0)];
        }

        return array_merge(array_values($groups), $rowsWithRecType);
    }

    /**
     * Returns the key of the group the given row is added to under a vat amount tolerance:
     * the first group with the same categories whose vat amount stays within the tolerance
     * from the vat of its net value, or a new one if none does. myDATA rejects a row whose
     * vat amount is more than 1.00 away from its net value times its vat rate (error 229),
     * so many rows with tiny rounding differences cannot be squashed into a single row.
     *
     * @param  array<string, array{0: float, 1: float}>  $groupTotals  The net value and vat amount of each group so far.
     */
    private function toleratedGroupKey(InvoiceDetails $row, float $tolerance, array $groupTotals): string
    {
        $rowKey = $this->generateRowKey($row);

        for ($index = 0; isset($groupTotals["$rowKey#$index"]); $index++) {
            [$netValue, $vatAmount] = $groupTotals["$rowKey#$index"];
            $netValue += $row->getNetValue() ?? 0;
            $vatAmount += $row->getVatAmount() ?? 0;

            if ($this->vatAmountDeviation($row->getVatCategory(), $netValue, $vatAmount) <= $tolerance) {
                return "$rowKey#$index";
            }
        }

        return "$rowKey#$index";
    }

    /**
     * Returns how far the vat amount is from the net value times the vat rate, both
     * rounded to cents the way the squashed row is sent. myDATA rounds the expected
     * vat half to even: 0.75 at 6% expects 0.04, not 0.05.
     */
    private function vatAmountDeviation(?VatCategory $vatCategory, float $netValue, float $vatAmount): float
    {
        $expectedVatAmount = round(round($netValue, 2) * ($vatCategory?->rate() ?? 0) / 100, 2, PHP_ROUND_HALF_EVEN);

        return round(abs(round($vatAmount, 2) - $expectedVatAmount), 2);
    }

    /**
     * The key of the group of a row: its GROUP_BY values, with a flag as '1' when true and
     * '' when false or unset (a false flag is never sent, so the two are the same row).
     */
    private function generateRowKey(InvoiceDetails $row): string
    {
        $key = '';
        foreach (self::GROUP_BY as $attribute) {
            $value = $row->get($attribute);
            $key .= ($value instanceof BackedEnum ? $value->value : ($value === true ? '1' : '')).'-';
        }

        return $key;
    }

    /**
     * The GROUP_BY values of a row, to start a squashed row with.
     *
     * @return array<string, mixed>
     */
    private function extractConcreteData(InvoiceDetails $row): array
    {
        $data = [];
        foreach (self::GROUP_BY as $attribute) {
            $data[$attribute] = $row->get($attribute);
        }

        return $data;
    }

    /**
     * Aggregates data from one row into another.
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
     * Aggregates income classifications for a given group.
     *
     * @param  IncomeClassification[]|null  $classifications
     */
    private function aggregateIncomeClassifications(int $groupIndex, ?array $classifications): void
    {
        if (empty($classifications)) {
            return;
        }

        foreach ($classifications as $classification) {
            $iclsKey = implode('-', [
                ($classification->getClassificationCategory()->value ?? ''),
                ($classification->getClassificationType()->value ?? ''),
            ]);

            $squashedIcls = $this->squashedIcls[$groupIndex][$iclsKey] ??= new IncomeClassification([
                'classificationCategory' => $classification->getClassificationCategory(),
                'classificationType' => $classification->getClassificationType(),
            ]);

            $squashedIcls->addAmount($classification->getAmount());
        }
    }

    /**
     * Aggregates expense classifications for a given group.
     *
     * @param  ExpensesClassification[]|null  $classifications
     */
    private function aggregateExpenseClassifications(int $groupIndex, ?array $classifications): void
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

            $squashedEcls = $this->squashedEcls[$groupIndex][$eclsKey] ??= new ExpensesClassification([
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
     * @return InvoiceDetails[]
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
        }

        foreach ($this->rowsWithRecType as $row) {
            $row->setLineNumber($lineNumber++);
        }

        return array_merge(array_values($this->squashedRows), $this->rowsWithRecType);
    }

    /**
     * @template TClassification of IncomeClassification|ExpensesClassification
     *
     * @param  array<string, TClassification>  $classifications
     * @return list<TClassification>
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
}

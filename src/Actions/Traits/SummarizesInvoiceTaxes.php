<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Actions\Traits;

use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\TaxTotals;
use InvalidArgumentException;

trait SummarizesInvoiceTaxes
{
    protected float $totalWithheldAmount = 0;

    protected float $totalFeesAmount = 0;

    protected float $totalStampDutyAmount = 0;

    protected float $totalOtherTaxesAmount = 0;

    protected float $totalDeductionsAmount = 0;

    protected function addTaxesFromInvoiceRow(InvoiceDetails $row): void
    {
        $this->totalWithheldAmount += abs($row->getWithheldAmount() ?? 0);
        $this->totalFeesAmount += abs($row->getFeesAmount() ?? 0);
        $this->totalStampDutyAmount += abs($row->getStampDutyAmount() ?? 0);
        $this->totalOtherTaxesAmount += abs($row->getOtherTaxesAmount() ?? 0);
        $this->totalDeductionsAmount += abs($row->getDeductionsAmount() ?? 0);
    }

    protected function addTaxesFromTaxTotals(TaxTotals $tax): void
    {
        $amount = abs($tax->getTaxAmount() ?? 0);

        match ($tax->getTaxType()) {
            TaxType::WITHHELD_TAX => $this->totalWithheldAmount += $amount,
            TaxType::FEES => $this->totalFeesAmount += $amount,
            TaxType::OTHER_TAXES => $this->totalOtherTaxesAmount += $amount,
            TaxType::STAMP_DUTY => $this->totalStampDutyAmount += $amount,
            TaxType::DEDUCTIONS => $this->totalDeductionsAmount += $amount,
            null => throw new InvalidArgumentException('A tax total without a tax type cannot be summarized.'),
        };
    }

    protected function saveTaxes(InvoiceSummary $summary): void
    {
        $withheldAmount = $this->round($summary->getTotalWithheldAmount() + $this->totalWithheldAmount);
        $summary->setTotalWithheldAmount($withheldAmount);

        $feesAmount = $this->round($summary->getTotalFeesAmount() + $this->totalFeesAmount);
        $summary->setTotalFeesAmount($feesAmount);

        $stampDutyAmount = $this->round($summary->getTotalStampDutyAmount() + $this->totalStampDutyAmount);
        $summary->setTotalStampDutyAmount($stampDutyAmount);

        $otherTaxesAmount = $this->round($summary->getTotalOtherTaxesAmount() + $this->totalOtherTaxesAmount);
        $summary->setTotalOtherTaxesAmount($otherTaxesAmount);

        $deductionsAmount = $this->round($summary->getTotalDeductionsAmount() + $this->totalDeductionsAmount);
        $summary->setTotalDeductionsAmount($deductionsAmount);
    }

    protected function getTotalTaxes(): float
    {
        return -$this->totalWithheldAmount
            - $this->totalDeductionsAmount
            + $this->totalFeesAmount
            + $this->totalStampDutyAmount
            + $this->totalOtherTaxesAmount;
    }

    protected function round(float $num, int $precision = 2): float
    {
        return round($num, $precision);
    }

    abstract public function saveTotals(InvoiceSummary $summary): void;
}

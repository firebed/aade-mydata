<?php

namespace Firebed\AadeMyData\Actions\Traits;

use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\TaxTotals;

trait SummarizesInvoiceTaxes
{
    protected float $totalWithheldAmount   = 0;
    protected float $totalFeesAmount       = 0;
    protected float $totalStampDutyAmount  = 0;
    protected float $totalOtherTaxesAmount = 0;
    protected float $totalDeductionsAmount = 0;

    /**
     * @var float Tax amounts that do not affect gross value
     * and should be excluded from total gross value.
     * @deprecated 
     */
    protected float $totalInformationalTaxAmount = 0;

    protected function addTaxesFromInvoiceRow(InvoiceDetails $row): void
    {
        $this->totalWithheldAmount += abs($row->getWithheldAmount() ?? 0);
        $this->totalFeesAmount += abs($row->getFeesAmount() ?? 0);
        $this->totalStampDutyAmount += abs($row->getStampDutyAmount() ?? 0);
        $this->totalOtherTaxesAmount += abs($row->getOtherTaxesAmount() ?? 0);
        $this->totalDeductionsAmount += abs($row->getDeductionsAmount() ?? 0);

        $withheldCategory = $row->getWithheldPercentCategory();
        if ($withheldCategory !== null && !$withheldCategory->affectsTotalGrossValue()) {
            $this->totalInformationalTaxAmount += abs($row->getWithheldAmount() ?? 0);
        }
    }

    protected function addTaxesFromTaxTotals(TaxTotals $tax): void
    {
        $amount = abs($tax->getTaxAmount());

        match ($tax->getTaxType()) {
            TaxType::TYPE_1 => $this->totalWithheldAmount += $amount,
            TaxType::TYPE_2 => $this->totalFeesAmount += $amount,
            TaxType::TYPE_3 => $this->totalOtherTaxesAmount += $amount,
            TaxType::TYPE_4 => $this->totalStampDutyAmount += $amount,
            TaxType::TYPE_5 => $this->totalDeductionsAmount += $amount,
        };

        if ($tax->getTaxType() === TaxType::TYPE_1) {
            $taxCategory = $tax->getTaxCategory() instanceof WithheldPercentCategory
                ? $tax->getTaxCategory()
                : WithheldPercentCategory::tryFrom($tax->getTaxCategory());

            if ($taxCategory !== null && !$taxCategory->affectsTotalGrossValue()) {
                $this->totalInformationalTaxAmount += $amount;
            }
        }
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
        
        $informationalTaxes = $this->round($summary->getTotalInformationalTaxAmount() + $this->totalInformationalTaxAmount);
        $summary->setTotalInformationalTaxAmount($informationalTaxes);
    }

    protected function getTotalTaxes(): float
    {
        return -$this->totalWithheldAmount
               -$this->totalDeductionsAmount
               +$this->totalFeesAmount
               +$this->totalStampDutyAmount
               +$this->totalOtherTaxesAmount;
    }

    protected function round(float $num, int $precision = 2): float
    {
        return round($num, $precision);
    }

    abstract function saveTotals(InvoiceSummary $summary): void;
}
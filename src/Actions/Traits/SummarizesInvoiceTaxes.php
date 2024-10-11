<?php

namespace Firebed\AadeMyData\Actions\Traits;

use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\TaxTotals;

trait SummarizesInvoiceTaxes
{
    public float $totalWithheldAmount   = 0;
    public float $totalFeesAmount       = 0;
    public float $totalStampDutyAmount  = 0;
    public float $totalOtherTaxesAmount = 0;
    public float $totalDeductionsAmount = 0;

    /**
     * @var float Tax amounts that do not affect gross value
     * and should be excluded from total gross value.
     */
    public float $totalInformationTaxAmount = 0;

    protected function addTaxesFromInvoiceRow(InvoiceDetails $row): void
    {
        $this->totalWithheldAmount += abs($row->getWithheldAmount() ?? 0);
        $this->totalFeesAmount += abs($row->getFeesAmount() ?? 0);
        $this->totalStampDutyAmount += abs($row->getStampDutyAmount() ?? 0);
        $this->totalOtherTaxesAmount += abs($row->getOtherTaxesAmount() ?? 0);
        $this->totalDeductionsAmount += abs($row->getDeductionsAmount() ?? 0);

        $withheldCategory = $row->getWithheldPercentCategory();
        if ($withheldCategory !== null && !$withheldCategory->affectsTotalGrossValue()) {
            $this->totalInformationTaxAmount += abs($row->getWithheldAmount() ?? 0);
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
                $this->totalInformationTaxAmount += $amount;
            }
        }
    }

    protected function saveTaxes(InvoiceSummary $summary): void
    {
        $this->updateTaxAmount($summary, 'TotalWithheldAmount', $this->totalWithheldAmount);
        $this->updateTaxAmount($summary, 'TotalFeesAmount', $this->totalFeesAmount);
        $this->updateTaxAmount($summary, 'TotalStampDutyAmount', $this->totalStampDutyAmount);
        $this->updateTaxAmount($summary, 'TotalOtherTaxesAmount', $this->totalOtherTaxesAmount);
        $this->updateTaxAmount($summary, 'TotalDeductionsAmount', $this->totalDeductionsAmount);
        $this->updateTaxAmount($summary, 'TotalInformationalTaxAmount', $this->totalInformationTaxAmount);
    }

    /**
     * Ενημέρωση των ποσών φόρων στο τιμολόγιο
     */
    private function updateTaxAmount(InvoiceSummary $summary, string $methodName, float $amount): void
    {
        $currentValue = $summary->{'get'.$methodName}();
        $summary->{'set'.$methodName}($this->round($currentValue + $amount));
        
    }

    public function getTotalTaxes(): float
    {
        return -$this->totalWithheldAmount
               -$this->totalDeductionsAmount
               +$this->totalInformationTaxAmount
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
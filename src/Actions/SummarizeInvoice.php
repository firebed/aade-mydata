<?php

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceSummary;

class SummarizeInvoice
{
    public function handle(Invoice $invoice, array $options = []): InvoiceSummary
    {
        $summary = new InvoiceSummary();

        $sumInvoiceRows = new SummarizeInvoiceRows();
        $sumInvoiceRows->handle($invoice->getInvoiceDetails(), $options);
        $sumInvoiceRows->saveTotals($summary);

        $sumInvoiceTaxes = new SummarizeInvoiceTaxes();
        $sumInvoiceTaxes->handle($invoice->getTaxesTotals(), $options);
        $sumInvoiceTaxes->saveTotals($summary);

        $classificationsGroup = new GroupClassifications();
        [$icls, $ecls] = $classificationsGroup->handle($invoice->getInvoiceDetails(), $options);

        $summary->setIncomeClassifications($icls);
        $summary->setExpensesClassifications($ecls);

        return $summary;
    }
}
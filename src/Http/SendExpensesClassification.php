<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\ResponseDoc;
use GuzzleHttp\Exception\GuzzleException;

class SendExpensesClassification extends MyDataRequest
{
    /**
     * With this method the user can classify invoices that produce income.
     *
     * @param InvoiceExpensesClassification[] $invoiceExpensesClassificationTypes
     * @throws GuzzleException
     */
    public function handle(array $invoiceExpensesClassificationTypes): ResponseDoc
    {
        return $this->post();
    }
}

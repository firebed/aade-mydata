<?php

namespace Firebed\AadeMyData\Http;

use Exception;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\ResponseDoc;

class SendExpensesClassification extends MyDataRequest
{
    /**
     * With this method the user can classify invoices that produce income.
     *
     * @param InvoiceExpensesClassification[] $invoiceExpensesClassificationTypes
     * @throws Exception
     */
    public function handle(array $invoiceExpensesClassificationTypes): ResponseDoc
    {
        throw new Exception('Not implemented');
    }
}

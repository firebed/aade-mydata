<?php

namespace Firebed\AadeMyData\Http;

use Exception;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\ResponseDoc;

class SendIncomeClassification extends MyDataRequest
{
    /**
     * With this method the user can classify invoices that produce income.
     *
     * @param InvoiceIncomeClassification[] $invoiceIncomeClassificationTypes
     * @throws Exception
     */
    public function handle(array $invoiceIncomeClassificationTypes): ResponseDoc
    {
        throw new Exception('Not implemented');
    }
}
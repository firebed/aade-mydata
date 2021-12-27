<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\InvoiceIncomeClassificationType;
use Firebed\AadeMyData\Models\ResponseDoc;
use GuzzleHttp\Exception\GuzzleException;

class SendIncomeClassification extends MyDataRequest
{
    /**
     * With this method the user can classify invoices that produce income.
     *
     * @param InvoiceIncomeClassificationType[] $invoiceIncomeClassificationTypes
     * @throws GuzzleException
     */
    public function handle(array $invoiceIncomeClassificationTypes): ResponseDoc
    {
        return $this->post();
    }
}
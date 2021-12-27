<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\InvoiceExpensesClassificationType;
use Firebed\AadeMyData\Models\ResponseDoc;

class SendExpensesClassification extends MyDataRequest
{
    /**
     * With this method the user can classify invoices that produce income.
     *
     * @param InvoiceExpensesClassificationType[] $invoiceExpensesClassificationTypes
     */
    public function handle(array $invoiceExpensesClassificationTypes): ResponseDoc
    {
        return $this->post();
    }
}

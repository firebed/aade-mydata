<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\InvoicesDocWriter;
use Firebed\AadeMyData\Xml\ResponseDocReader;

class SendInvoices extends MyDataXmlRequest
{
    /**
     * <p>Το σώμα της κλήσης θα πρέπει είναι σε μορφή xml και περιέχει
     * το στοιχείο InvoicesDoc, το οποίο περιέχει ένα ή περισσότερα παραστατικά.</p>
     *
     * @param InvoicesDoc|Invoice|Invoice[] $invoices InvoicesDoc
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function handle(InvoicesDoc|Invoice|array $invoices): ResponseDoc
    {
        if (!$invoices instanceof InvoicesDoc) {
            $invoices = new InvoicesDoc($invoices);
        }

        return $this->request(new InvoicesDocWriter(), new ResponseDocReader(), $invoices);
    }
}
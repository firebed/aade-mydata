<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Parser\InvoicesDocXML;
use GuzzleHttp\Exception\GuzzleException;

class SendInvoices extends MyDataRequest
{
    /**
     * <p>Το σώμα της κλήσης θα πρέπει είναι σε μορφή xml και περιέχει
     * το στοιχείο InvoicesDoc, το οποίο περιέχει ένα ή περισσότερα παραστατικά.</p>
     *
     * @param InvoicesDoc $invoicesDoc InvoicesDoc
     * @throws GuzzleException
     */
    public function handle(InvoicesDoc $invoicesDoc): ResponseDoc
    {
        $body = (new InvoicesDocXML())->asXML($invoicesDoc);
        return $this->post(body: $body);
    }
}
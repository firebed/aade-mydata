<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasRequestXML;
use Firebed\AadeMyData\Http\Traits\HasResponseXML;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\InvoicesDocWriter;
use Firebed\AadeMyData\Xml\ResponseDocReader;

class SendInvoices extends MyDataRequest
{
    use HasRequestXML;
    use HasResponseXML;

    /**
     * <p>Το σώμα της κλήσης θα πρέπει είναι σε μορφή xml και περιέχει
     * το στοιχείο InvoicesDoc, το οποίο περιέχει ένα ή περισσότερα παραστατικά.</p>
     *
     * @param InvoicesDoc|Invoice|Invoice[] $invoicesDoc InvoicesDoc
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function handle(InvoicesDoc|Invoice|array $invoicesDoc): ResponseDoc
    {
        if (!$invoicesDoc instanceof InvoicesDoc) {
            $invoicesDoc = new InvoicesDoc(is_array($invoicesDoc) ? $invoicesDoc : [$invoicesDoc]);
        }
        
        $writer = new InvoicesDocWriter();
        $this->requestXML = $writer->asXML($invoicesDoc);

        $response = $this->post(body: $this->requestXML);
        $this->responseXML = $response->getBody()->getContents();

        $reader = new ResponseDocReader();
        return $reader->parseXML($this->responseXML);
    }
}
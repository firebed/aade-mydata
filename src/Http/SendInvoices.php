<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Http\Traits\HasRequestXML;
use Firebed\AadeMyData\Http\Traits\HasResponseXML;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\InvoicesDocReader;
use Firebed\AadeMyData\Xml\ResponseDocReader;
use GuzzleHttp\Exception\GuzzleException;

class SendInvoices extends MyDataRequest
{
    use HasRequestXML;
    use HasResponseXML;
    
    /**
     * <p>Το σώμα της κλήσης θα πρέπει είναι σε μορφή xml και περιέχει
     * το στοιχείο InvoicesDoc, το οποίο περιέχει ένα ή περισσότερα παραστατικά.</p>
     *
     * @param InvoicesDoc $invoicesDoc InvoicesDoc
     * @throws GuzzleException
     */
    public function handle(InvoicesDoc $invoicesDoc): ResponseDoc
    {
        $this->requestXML = (new InvoicesDocReader())->asXML($invoicesDoc);

        $response = $this->post(body: $this->requestXML);
        $this->responseXML = $response->getBody()->getContents();
        
        return (new ResponseDocReader())->parseXML($this->responseXML);
    }
}
<?php

namespace Tests\Traits;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\InvoicesDocWriter;
use Firebed\AadeMyData\Xml\RequestedDocReader;
use Firebed\AadeMyData\Xml\ResponseDocReader;
use Tests\Xml\Document;

trait HandlesInvoiceXml
{
    use UsesStubs;

    public function getInvoiceFromXml(string $filename = 'request-doc-response'): Invoice
    {
        $xmlString = $this->getStub($filename);

        $parser = new RequestedDocReader();
        $requestedDoc = $parser->parseXML($xmlString);

        return $requestedDoc->getInvoices()->offsetGet(0);
    }

    public function getRequestedDocFromXml(string $filename = 'request-doc-response'): RequestedDoc
    {
        $xmlString = $this->getStub($filename);
        $parser = new RequestedDocReader();

        return $parser->parseXML($xmlString);
    }

    public function getResponseDocFromXml(string $filename): ResponseDoc
    {
        $xmlString = $this->getStub($filename);
        $parser = new ResponseDocReader();

        return $parser->parseXML($xmlString);
    }

    protected function toXML(Invoice $invoice): object
    {
        $writer = new InvoicesDocWriter();

        // First convert the Invoice to XML
        $xmlString = $writer->asXML(new InvoicesDoc($invoice));

        // Return the XML as a Document object
        return new Document($xmlString);
    }
}
<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Xml\InvoicesDocWriter;
use Firebed\AadeMyData\Xml\RequestedDocReader;

class RequestedDocXmlFactory
{
    private array $invoices = [];

    public function addInvoice(Invoice $invoice): void
    {
        $this->invoices[] = $invoice;;
    }

    public function toRequestedDoc(): RequestedDoc
    {
        // First we need to transform the Invoice object to an XML string
        $writer = new InvoicesDocWriter();
        $invoicesDocXmlString = $writer->asXML(new InvoicesDoc($this->invoices));

        $invoicesDocXmlString = str_replace('InvoicesDoc', 'RequestedDoc', $invoicesDocXmlString);

        // Then we need to transform the XML string to RequestedDoc object
        $reader = new RequestedDocReader();
        return $reader->parseXML($invoicesDocXmlString);
    }
}
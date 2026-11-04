<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\InvoicesDocWriter;
use Firebed\AadeMyData\Xml\ResponseDocReader;

/**
 * A request that sends an InvoicesDoc: SendInvoices, and SendUnsignedInvoices for invoices
 * under issue. They are different myDATA actions with the same body, so they share this base
 * instead of one extending the other.
 */
abstract class InvoicesDocRequest extends MyDataXmlRequest
{
    protected const ?string SCHEMA = 'InvoicesDoc';

    private ?InvoicesDoc $invoicesDoc = null;

    /**
     * <p>Το σώμα της κλήσης θα πρέπει είναι σε μορφή xml και περιέχει
     * το στοιχείο InvoicesDoc, το οποίο περιέχει ένα ή περισσότερα παραστατικά.</p>
     *
     * @param  InvoicesDoc|Invoice|list<Invoice>  $invoices
     *
     * @throws MyDataException
     */
    public function handle(InvoicesDoc|Invoice|array $invoices): ResponseDoc
    {
        // Before the invoices are kept, so a request on the wrong channel leaves none behind
        $this->ensureChannel();

        if (! $invoices instanceof InvoicesDoc) {
            $invoices = new InvoicesDoc($invoices);
        }

        $this->invoicesDoc = $invoices;

        return $this->request(new InvoicesDocWriter, new ResponseDocReader, $invoices);
    }

    /**
     * The invoices handed to handle(), as models. A Gateway that routes through an
     * e-invoicing provider reads them here instead of re-parsing the request XML, so
     * attributes that never reach the myDATA XML (e.g. InvoiceHeader::setIssueTime())
     * stay available to it.
     */
    public function getInvoicesDoc(): ?InvoicesDoc
    {
        return $this->invoicesDoc;
    }
}

<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\ResponseDoc;

/**
 * Invoices under issue (decision Α.1170/2023) that were not issued in the end: the
 * provider transmits their providers signatures, and myDATA gives them no mark.
 * Technically identical to SendInvoices; provider channel only.
 */
class SendUnsignedInvoices extends SendInvoices
{
    protected string $action = 'SendUnsignedInvoices';

    /**
     * @param  InvoicesDoc|Invoice|list<Invoice>  $invoices
     *
     * @throws UnsupportedChannelException When the connection is not the provider channel
     * @throws MyDataException
     */
    public function handle(InvoicesDoc|Invoice|array $invoices): ResponseDoc
    {
        $this->ensureProvider();

        return parent::handle($invoices);
    }
}

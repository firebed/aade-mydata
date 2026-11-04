<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

/**
 * Invoices under issue ("Υπό Έκδοση") that were not issued: the provider created a Provider's
 * Signature for a POS or instant (IRIS) payment of the invoice, and the signature was never
 * matched with an issued invoice and payment. Decision Α.1155/2023 (art. 4 and 4A par. 2)
 * requires the provider to transmit such invoices to AADE marked "Υπό Έκδοση"; this call is
 * that marking, since the InvoicesDoc schema has no field for it. A transaction that did not
 * complete, whose signature the payment terminal rejects, is to be recorded by the provider
 * (Α.1160/2025, art. 3 par. 2γ), which names no transmission; see docs/http/send-unsigned-invoices.md. Same body as SendInvoices (the whole
 * invoice, the signature in its payment method); provider channel only.
 *
 * Observed on the provider sandbox (November 2026): myDATA answers Success with the uid and an
 * invoiceMark from a separate, shorter range (8 digits there), with no authenticationCode and no
 * qrUrl. It accepted the same invoice twice, each time with a new mark, so sending each
 * invoice once is the caller's job. A later SendInvoices of the same uid is accepted as a
 * normal invoice. The provider RequestTransmittedDocs (RequestProviderDocs) does not list it.
 */
class SendUnsignedInvoices extends InvoicesDocRequest
{
    protected string $action = 'SendUnsignedInvoices';

    protected const ?Channel CHANNEL = Channel::Provider;
}

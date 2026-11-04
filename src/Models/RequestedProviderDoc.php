<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * The invoices a provider transmitted for one issuer, as the provider RequestTransmittedDocs
 * answers (RequestedProviderDoc). myDATA lists an invoice only once it has stored it, which
 * can be hours after it answered the SendInvoices that registered it.
 */
class RequestedProviderDoc extends Model implements Paginated
{
    protected function casts(): array
    {
        return [
            'continuationToken' => ContinuationToken::class,
            'InvoiceProviderType' => Arr::of(ProviderInvoice::class),
        ];
    }

    /**
     * @return ContinuationToken|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function getContinuationToken(): ?ContinuationToken
    {
        return $this->get('continuationToken');
    }

    /**
     * @return list<ProviderInvoice> Λίστα Στοιχείων Παραστατικών
     */
    public function getInvoices(): array
    {
        return $this->get('InvoiceProviderType') ?? [];
    }

    /**
     * The invoices with this uid. More than one means myDATA registered the same invoice twice.
     *
     * @return list<ProviderInvoice>
     */
    public function findByUid(string $uid): array
    {
        return array_values(array_filter($this->getInvoices(), fn (ProviderInvoice $invoice) => strcasecmp((string) $invoice->getInvoiceUid(), $uid) === 0));
    }
}

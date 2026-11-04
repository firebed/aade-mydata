<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

/**
 * An invoice the provider transmitted, as the provider RequestTransmittedDocs lists it
 * (InvoiceProviderType): who issued it, the mark and uid myDATA gave it and its
 * authentication code.
 */
class ProviderInvoice extends Model
{
    /**
     * @return string|null Α.Φ.Μ. Εκδότη Παραστατικού
     */
    public function getIssuerVAT(): ?string
    {
        return $this->get('issuerVAT');
    }

    /**
     * @return int|null ΜΑΡΚ παραστατικού
     */
    public function getInvoiceProviderMark(): ?int
    {
        return $this->get('invoiceProviderMark');
    }

    /**
     * @return string|null Αναγνωριστικό Παραστατικού
     */
    public function getInvoiceUid(): ?string
    {
        return $this->get('invoiceUid');
    }

    /**
     * @return string|null Συμβολοσειρά Αυθεντικοποίησης Παραστατικού Παρόχου
     */
    public function getAuthenticationCode(): ?string
    {
        return $this->get('authenticationCode');
    }
}

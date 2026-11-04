<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

/**
 * A provider the receiving entity invoices through (ProviderInfoType).
 */
class ProviderInfo extends Model
{
    /**
     * @return string|null ΑΦΜ Παρόχου
     */
    public function getProviderVATNumber(): ?string
    {
        return $this->get('providerVATNumber');
    }
}

<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * The providers through which an entity invoices (receptionProviders).
 *
 * @extends ModelCollection<ProviderInfo>
 */
class ReceptionProviders extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'ProviderInfo' => Arr::of(ProviderInfo::class),
        ];
    }

    /**
     * @return list<string> The VAT numbers of the providers
     */
    public function vatNumbers(): array
    {
        return array_values(array_filter($this->map(fn (ProviderInfo $provider) => $provider->getProviderVATNumber())));
    }
}

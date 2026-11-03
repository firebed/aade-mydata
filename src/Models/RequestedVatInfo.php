<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

class RequestedVatInfo extends Model implements Paginated
{
    protected function casts(): array
    {
        return [
            'continuationToken' => ContinuationToken::class,
            'VatInfo' => Arr::of(VatInfo::class),
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
     * @return VatInfo[]|null Λίστα στοιχείων εισροών και εκροών ανά παραστατικό
     */
    public function getVatInfo(): ?array
    {
        return $this->get('VatInfo');
    }
}

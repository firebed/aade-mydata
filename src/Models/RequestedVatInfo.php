<?php

namespace Firebed\AadeMyData\Models;

class RequestedVatInfo extends Type
{
    protected array $casts = [
        'continuationToken' => ContinuationToken::class,
        'VatInfo'           => VatInfo::class,
    ];

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

    public function set($key, $value): void
    {
        if ($key === 'VatInfo') {
            $this->push($key, $value);
            return;
        }

        parent::set($key, $value);
    }
}
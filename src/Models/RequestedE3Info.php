<?php

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.10
 */
class RequestedE3Info extends Type
{
    protected array $casts = [
        'continuationToken' => ContinuationToken::class,
        'E3Info' => E3Info::class,
    ];

    /**
     * @return ContinuationToken|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     * @version 1.0.10
     */
    public function getContinuationToken(): ?ContinuationToken
    {
        return $this->get('continuationToken');
    }

    /**
     * @return E3Info[]|null Λίστα στοιχείων E3
     * @version 1.0.10
     */
    public function getE3Info(): ?array
    {
        return $this->get('E3Info');
    }

    public function set($key, $value): static
    {
        if ($key === 'E3Info' && !is_array($value)) {
            return $this->push($key, $value);
        }

        return parent::set($key, $value);
    }
}
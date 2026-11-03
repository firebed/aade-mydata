<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * @version 1.0.10
 */
class RequestedE3Info extends Model implements Paginated
{
    protected function casts(): array
    {
        return [
            'continuationToken' => ContinuationToken::class,
            'E3Info' => Arr::of(E3Info::class),
        ];
    }

    /**
     * @return ContinuationToken|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     *
     * @version 1.0.10
     */
    public function getContinuationToken(): ?ContinuationToken
    {
        return $this->get('continuationToken');
    }

    /**
     * @return E3Info[]|null Λίστα στοιχείων E3
     *
     * @version 1.0.10
     */
    public function getE3Info(): ?array
    {
        return $this->get('E3Info');
    }
}

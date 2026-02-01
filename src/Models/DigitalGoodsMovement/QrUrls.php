<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * @extends TypeArray<string>
 * @version 2.0.1
 */
class QrUrls extends TypeArray
{
    /**
     * @param array<string>|null $items Λίστα με τα URL των QR code προς ομαδοποίηση.
     */
    public function __construct(array $items = null)
    {
        parent::__construct('qrUrl', $items);
    }

    public function set($key, $value): static
    {
        return $this->push('qrUrl', $value);
    }
}
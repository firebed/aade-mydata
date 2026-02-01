<?php

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;

enum PackagingType: int
{
    use HasLabels;

    case PALLET = 1;
    case BOX = 2;
    case CRATE = 3;
    case BARREL = 4;
    case SACK = 5;
    case OTHER = 6;

    public function label(): string
    {
        return match ($this) {
            self::PALLET => 'Παλέτα',
            self::BOX => 'Κούτα',
            self::CRATE => 'Κιβώτιο',
            self::BARREL => 'Βαρέλι',
            self::SACK => 'Σάκος',
            self::OTHER => 'Λοιπά',
        };
    }
}

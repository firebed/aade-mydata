<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;

enum PackagingType: int
{
    use HasLabels;

    #[Label('Παλέτα')]
    case PALLET = 1;
    #[Label('Κούτα')]
    case BOX = 2;
    #[Label('Κιβώτιο')]
    case CRATE = 3;
    #[Label('Βαρέλι')]
    case BARREL = 4;
    #[Label('Σάκος')]
    case SACK = 5;
    #[Label('Λοιπά')]
    case OTHER = 6;
}

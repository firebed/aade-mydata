<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;

enum TransportType: int
{
    use HasLabels;

    #[Label('Φορτηγό Δημόσιας Χρήσης')]
    case PUBLIC_USE_TRUCK = 1;
    #[Label('Φορτηγό Ιδιωτικής Χρήσης')]
    case PRIVATE_USE_TRUCK = 2;
    #[Label('Πλοίο')]
    case SHIP = 3;
    #[Label('Τρένο')]
    case TRAIN = 4;
    #[Label('Αεροπλάνο')]
    case AIRPLANE = 5;
    #[Label('Λοιπά Μεταφορικά Μέσα')]
    case OTHER_TRANSPORT_MEANS = 6;
    #[Label('Άνευ')]
    case WITHOUT = 7;
}

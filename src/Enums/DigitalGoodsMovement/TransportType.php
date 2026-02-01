<?php

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;

enum TransportType: int
{
    use HasLabels;

    case PUBLIC_USE_TRUCK = 1;
    case PRIVATE_USE_TRUCK = 2;
    case SHIP = 3;
    case TRAIN = 4;
    case AIRPLANE = 5;
    case OTHER_TRANSPORT_MEANS = 6;
    case WITHOUT = 7;

    public function label(): string
    {
        return match ($this) {
            self::PUBLIC_USE_TRUCK => "Φορτηγό Δημόσιας Χρήσης",
            self::PRIVATE_USE_TRUCK => "Φορτηγό Ιδιωτικής Χρήσης",
            self::SHIP => "Πλοίο",
            self::TRAIN => "Τρένο",
            self::AIRPLANE => "Αεροπλάνο",
            self::OTHER_TRANSPORT_MEANS => "Λοιπά Μεταφορικά Μέσα",
            self::WITHOUT => "Άνευ",
        };
    }
}
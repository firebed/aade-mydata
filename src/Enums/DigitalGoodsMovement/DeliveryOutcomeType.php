<?php

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;

enum DeliveryOutcomeType: string
{
    use HasLabels;

    case FULL = 'FULL';
    case PARTIAL = 'PARTIAL';
    case NONE = 'NONE';

    public function label(): string
    {
        return match ($this) {
            self::FULL => 'Πλήρης Παράδοση',
            self::PARTIAL => 'Μερική Παράδοση',
            self::NONE => 'Καμία Παράδοση',
        };
    }
}

<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;

enum DeliveryOutcomeType: string
{
    use HasLabels;

    #[Label('Πλήρης Παράδοση')]
    case FULL = 'FULL';
    #[Label('Μερική Παράδοση')]
    case PARTIAL = 'PARTIAL';
    #[Label('Καμία Παράδοση')]
    case NONE = 'NONE';
}

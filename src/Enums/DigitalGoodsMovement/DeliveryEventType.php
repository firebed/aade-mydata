<?php

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;

enum DeliveryEventType: string
{
    use HasLabels;

    case REGISTER_TRANSFER = 'RegisterTransfer';
    case CONFIRM_OUTCOME = 'ConfirmOutcome';
    case REJECTION = 'Rejection';

    public function label(): string
    {
        return match ($this) {
            self::REGISTER_TRANSFER => 'Έναρξη διακίνησης',
            self::CONFIRM_OUTCOME => 'Επιβεβαίωση παραλαβής',
            self::REJECTION => 'Απόρριψη',
        };
    }
}

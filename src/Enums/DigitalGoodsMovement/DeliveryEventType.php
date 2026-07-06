<?php

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;

enum DeliveryEventType: string
{
    use HasLabels;

    case REGISTER_TRANSFER = 'RegisterTransfer';
    case CONFIRM_OUTCOME = 'ConfirmOutcome';
    case REJECTION = 'Rejection';
    case CONFIRM_RETURN = 'ConfirmReturn';
    case REGISTER_TRANSFER_RETURN = 'RegisterTransferReturn';

    public function label(): string
    {
        return match ($this) {
            self::REGISTER_TRANSFER => 'Έναρξη διακίνησης',
            self::CONFIRM_OUTCOME => 'Επιβεβαίωση παραλαβής',
            self::REJECTION => 'Απόρριψη',
            self::CONFIRM_RETURN => 'Επιβεβαίωση επιστροφής',
            self::REGISTER_TRANSFER_RETURN => 'Επιστροφή διακίνησης',
        };
    }
}

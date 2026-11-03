<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;

enum DeliveryEventType: string
{
    use HasLabels;

    #[Label('Έναρξη διακίνησης')]
    case REGISTER_TRANSFER = 'RegisterTransfer';
    #[Label('Επιβεβαίωση παραλαβής')]
    case CONFIRM_OUTCOME = 'ConfirmOutcome';
    #[Label('Απόρριψη')]
    case REJECTION = 'Rejection';
    #[Label('Επιβεβαίωση επιστροφής')]
    case CONFIRM_RETURN = 'ConfirmReturn';
    #[Label('Επιστροφή διακίνησης')]
    case REGISTER_TRANSFER_RETURN = 'RegisterTransferReturn';
}

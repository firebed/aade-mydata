<?php

namespace Firebed\AadeMyData\Types;

use Firebed\AadeMyData\Enums\HasLabels;

enum RelatedEntity: int
{
    use HasLabels;

    /**
     * Φορολογικός Εκπρόσωπος
     */
    case TAX_REPRESENTATIVE = 1;

    /**
     * Διαμεσολαβητής
     */
    case INTERMEDIARY = 2;

    /**
     * Μεταφορέας
     */
    case TRANSPORTER = 3;

    /**
     * Λήπτης του Αποστολέα (Πωλητή)
     */
    case RECEIVER = 4;

    /**
     * Αποστολέας (Πωλητής)
     */
    case SENDER = 5;

    /**
     * Λοιπές Συσχετιζόμενες Οντότητες
     */
    case OTHER = 6;

    public function label(): string
    {
        return match ($this) {
            self::TAX_REPRESENTATIVE => "Φορολογικός Εκπρόσωπος",
            self::INTERMEDIARY => "Διαμεσολαβητής",
            self::TRANSPORTER => "Μεταφορέας",
            self::RECEIVER => "Λήπτης του Αποστολέα (Πωλητή)",
            self::SENDER => "Αποστολέας (Πωλητής)",
            self::OTHER => "Λοιπές Συσχετιζόμενες Οντότητες",
        };
    }
}

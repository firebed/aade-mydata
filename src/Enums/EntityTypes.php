<?php

namespace Firebed\AadeMyData\Enums;

enum EntityTypes: int
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
    case CARRIER = 3;


    /**
     * Λήπτης του Αποστολέα (Πωλητή)
     */
    case RECIPIENT_OF_SENDER = 4;


    /**
     * Αποστολέας (Πωλητής)
     */
    case SENDER = 5;


    /**
     * Λοιπές Συσχετιζόμενες Οντότητες
     */
    case OTHER_RELATED_ENTITIES = 6;

    public function label(): string
    {
        return match ($this) {
            self::TAX_REPRESENTATIVE => "Φορολογικός Εκπρόσωπος",
            self::INTERMEDIARY => "Διαμεσολαβητής",
            self::CARRIER => "Μεταφορέας",
            self::RECIPIENT_OF_SENDER => "Λήπτης του Αποστολέα (Πωλητή)",
            self::SENDER => "Αποστολέας (Πωλητής)",
            self::OTHER_RELATED_ENTITIES => "Λοιπές Συσχετιζόμενες Οντότητες",
        };
    }
}

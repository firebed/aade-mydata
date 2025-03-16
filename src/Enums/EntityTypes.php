<?php

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Types\RelatedEntity;

/**
 * @deprecated
 * @see RelatedEntity
 */
enum EntityTypes: int
{
    use HasLabels;

    /**
     * Φορολογικός Εκπρόσωπος
     * @deprecated
     * @see RelatedEntity::TAX_REPRESENTATIVE
     */
    case TYPE_1 = 1;


    /**
     * Διαμεσολαβητής
     * @deprecated
     * @see RelatedEntity::INTERMEDIARY
     */
    case TYPE_2 = 2;


    /**
     * Μεταφορέας
     * @deprecated
     * @see RelatedEntity::TRANSPORTER
     */
    case TYPE_3 = 3;


    /**
     * Λήπτης του Αποστολέα (Πωλητή)
     * @deprecated
     * @see RelatedEntity::RECEIVER
     */
    case TYPE_4 = 4;


    /**
     * Αποστολέας (Πωλητής)
     * @deprecated
     * @see RelatedEntity::SENDER
     */
    case TYPE_5 = 5;


    /**
     * Λοιπές Συσχετιζόμενες Οντότητες
     * @deprecated
     * @see RelatedEntity::OTHER
     */
    case TYPE_6 = 6;

    public function label(): string
    {
        return match ($this) {
            self::TYPE_1 => "Φορολογικός Εκπρόσωπος",
            self::TYPE_2 => "Διαμεσολαβητής",
            self::TYPE_3 => "Μεταφορέας",
            self::TYPE_4 => "Λήπτης του Αποστολέα (Πωλητή)",
            self::TYPE_5 => "Αποστολέας (Πωλητής)",
            self::TYPE_6 => "Λοιπές Συσχετιζόμενες Οντότητες",
        };
    }
}

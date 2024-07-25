<?php

namespace Firebed\AadeMyData\Enums;

enum EntityTypes: int
{
    use HasLabels;
    
    /**
     * Φορολογικός Εκπρόσωπος
     */
    case TYPE_1 = 1;
    
    
    /**
     * Διαμεσολαβητής
     */
    case TYPE_2 = 2;
    
    
    /**
     * Μεταφορέας
     */
    case TYPE_3 = 3;
    
    
    /**
     * Λήπτης του Αποστολέα (Πωλητή)
     */
    case TYPE_4 = 4;
    
    
    /**
     * Αποστολέας (Πωλητής)
     */
    case TYPE_5 = 5;
    
    
    /**
     * Λοιπές Συσχετιζόμενες Οντότητες
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

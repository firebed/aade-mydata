<?php

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Enums\Traits\HasLabels;

enum TaxType: int
{
    use HasLabels;
    
    /**
     * Παρακρατούμενος Φόρος
     */
    case TYPE_1 = 1;

    /**
     * Τέλη
     */
    case TYPE_2 = 2;

    /**
     * Λοιποί Φόροι
     */
    case TYPE_3 = 3;
    
    /**
     * Χαρτόσημο
     */
    case TYPE_4 = 4;
    
    /**
     * Κρατήσεις
     */
    case TYPE_5 = 5;

    public function label(): string
    {
        return match($this) {
            self::TYPE_1 => "Παρακρατούμενος Φόρος",
            self::TYPE_2 => "Τέλη",
            self::TYPE_3 => "Λοιποί Φόροι",
            self::TYPE_4 => "Χαρτόσημο",
            self::TYPE_5 => "Κρατήσεις",
        };
    }
}
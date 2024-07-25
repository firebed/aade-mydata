<?php

namespace Firebed\AadeMyData\Enums;

enum UnitMeasurement: int
{
    use HasLabels;
    
    /**
     *  Τεμάχια
     */
    case UNIT_1 = 1;


    /**
     *  Κιλά
     */
    case UNIT_2 = 2;


    /**
     *  Λίτρα
     */
    case UNIT_3 = 3;

    /**
     * Μέτρα
     */
    case UNIT_4 = 4;
    
    
    /**
     * Τετραγωνικά Μέτρα
     */
    case UNIT_5 = 5;
    
    
    /**
     * Κυβικά Μέτρα
     */
    case UNIT_6 = 6;
    
    
    /**
     * Τεμάχια_Λοιπές Περιπτώσεις
     */
    case UNIT_7 = 7;
    
    public function label(): string
    {
        return match ($this) {
            self::UNIT_1 => "Τεμάχια",
            self::UNIT_2 => "Κιλά",
            self::UNIT_3 => "Λίτρα",
            self::UNIT_4 => "Μέτρα",
            self::UNIT_5 => "Τετραγωνικά Μέτρα",
            self::UNIT_6 => "Κυβικά Μέτρα",
            self::UNIT_7 => "Τεμάχια_Λοιπές Περιπτώσεις",
        };
    }
}
<?php

namespace Firebed\AadeMyData\Enums;

enum UnitMeasurement: int
{
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
}
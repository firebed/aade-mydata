<?php

namespace Firebed\AadeMyData\Enums;

enum TaxType: int
{
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
}
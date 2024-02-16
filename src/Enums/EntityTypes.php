<?php

namespace Firebed\AadeMyData\Enums;

enum EntityTypes: int
{
    /**
     *  Φορολογικός Εκπρόσωπος
     */
    case TYPE_1 = 1;
    
    
    /**
     *  Διαμεσολαβητής
     */
    case TYPE_2 = 2;
    
    
    /**
     *  Μεταφορέας
     */
    case TYPE_3 = 3;
    
    
    /**
     *  Λήπτης του Αποστολέα (Πωλητή)
     */
    case TYPE_4 = 4;
    
    
    /**
     *  Αποστολέας (Πωλητής)
     */
    case TYPE_5 = 5;
    
    
    /**
     *  Λοιπές Συσχετιζόμενες Οντότητες
     */
    case TYPE_6 = 6;
}

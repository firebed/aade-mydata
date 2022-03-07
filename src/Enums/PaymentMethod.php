<?php

namespace Firebed\AadeMyData\Enums;

enum PaymentMethod: string
{
    /**
     *  Επαγ. Λογαριασμός Πληρωμών Ημεδαπής
     */
    case METHOD_1 = "1";


    /**
     *  Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής
     */
    case METHOD_2 = "2";


    /**
     *  Μετρητά
     */
    case METHOD_3 = "3";


    /**
     *  Επιταγή
     */
    case METHOD_4 = "4";


    /**
     *  Επί Πιστώσει
     */
    case METHOD_5 = "5";

    /**
     * Web Banking
     */
    case METHOD_6 = "6";

    /**
     * POS / e-POS
     */
    case METHOD_7 = "7";
}
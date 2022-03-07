<?php

namespace Firebed\AadeMyData\Enums;

enum MovePurpose: string
{
    /**
     *  Πώληση
     */
    case TYPE_1 = "1";


    /**
     *  Πώληση για Λογαριασμό Τρίτων
     */
    case TYPE_2 = "2";


    /**
     *  Δειγματισμός
     */
    case TYPE_3 = "3";


    /**
     *  Έκθεση
     */
    case TYPE_4 = "4";


    /**
     *  Επιστροφή
     */
    case TYPE_5 = "5";


    /**
     *  Φύλαξη
     */
    case TYPE_6 = "6";


    /**
     *  Επεξεργασία Συναρμολόγηση
     */
    case TYPE_7 = "7";


    /**
     *  Μεταξύ Εγκαταστάσεων Οντότητας
     */
    case TYPE_8 = "8";
}
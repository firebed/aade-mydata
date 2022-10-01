<?php

namespace Firebed\AadeMyData\Enums;

enum SpecialInvoiceCategory: int
{
    /**
     *  Επιδοτήσεις – Επιχορηγήσεις
     */
    case TYPE_1 = 1;
    
    
    /**
     *  Έσοδα Λιανικής Ξενοδοχείων – Χρεώσεις Δωματίου
     */
    case TYPE_2 = 2;
    
    
    /**
     *  Λογιστική Εγγραφή
     */
    case TYPE_3 = 3;
    
    
    /**
     *  Tax Free Έγκυρη τιμή μόνο για διαβίβαση μέσω erp ή έκδοση μέσω παρόχου ή timologio
     */
    case TYPE_4 = 4;
}
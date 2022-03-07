<?php

namespace Firebed\AadeMyData\Enums;

enum InvoiceDetailType: string
{
    /**
     *  Εκκαθάριση Πωλήσεων Τρίτων
     */
    case TYPE_1 = "1";

    
    /**
     *  Αμοιβή από Πωλήσεις Τρίτων
     */
    case TYPE_2 = "2";
}
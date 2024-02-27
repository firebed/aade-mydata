<?php

namespace Firebed\AadeMyData\Enums;

enum TransactionMode: int
{
    /**
     * Αποδοχή του παραστατικού
     */
    case ACCEPT = 0;
    
    /**
     * Απόρριψη του παραστατικού λόγω διαφωνίας 
     */
    case REJECT = 1;


    /**
     * Απόρριψη του παραστατικού λόγω απόκλισης στα ποσά
     */
    case DEVIATION = 2;
}
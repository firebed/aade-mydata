<?php

namespace Firebed\AadeMyData\Enums;

enum TransactionMode: int
{
    /**
     * Απόρριψη του παραστατικού λόγω διαφωνίας 
     */
    case REJECT = 1;


    /**
     * Απόρριψη του παραστατικού λόγω απόκλισης στα ποσά
     */
    case DEVIATION = 2;
}
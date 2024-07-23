<?php

namespace Firebed\AadeMyData\Enums;

enum TransactionMode: int
{
    use HasLabels;
    
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
    
    public function label(): string
    {
        return match ($this) {
            self::ACCEPT => "Αποδοχή του παραστατικού",
            self::REJECT => "Απόρριψη του παραστατικού λόγω διαφωνίας",
            self::DEVIATION => "Απόρριψη του παραστατικού λόγω απόκλισης στα ποσά",
        };
    }
}
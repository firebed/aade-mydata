<?php

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Enums\Traits\HasLabels;

enum InvoiceDetailType: int
{
    use HasLabels;
    
    /**
     *  Εκκαθάριση Πωλήσεων Τρίτων
     */
    case TYPE_1 = 1;

    
    /**
     *  Αμοιβή από Πωλήσεις Τρίτων
     */
    case TYPE_2 = 2;

    public function label(): string
    {
        return match ($this) {
            self::TYPE_1 => "Εκκαθάριση Πωλήσεων Τρίτων",
            self::TYPE_2 => "Αμοιβή από Πωλήσεις Τρίτων",
        };
    }
}
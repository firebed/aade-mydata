<?php

namespace Firebed\AadeMyData\Enums;

enum StampCategory: int
{
    use HasLabels;
    
    /**
     *  Συντελεστής 1,2 % [1,20%]
     */
    case TYPE_1 = 1;


    /**
     *  Συντελεστής 2,4 % [2,40%]
     */
    case TYPE_2 = 2;


    /**
     *  Συντελεστής 3,6 % [3,60%]
     */
    case TYPE_3 = 3;


    /**
     *  Λοιπές περιπτώσεις Χαρτοσήμου [ποσό]
     */
    case TYPE_4 = 4;

    public function label(): string
    {
        return match ($this) {
            self::TYPE_1 => "Συντελεστής 1.2 %",
            self::TYPE_2 => "Συντελεστής 2.4 %",
            self::TYPE_3 => "Συντελεστής 3.6 %",
            self::TYPE_4 => "Λοιπές περιπτώσεις Χαρτοσήμου",
        };
    }
}
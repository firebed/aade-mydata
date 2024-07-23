<?php

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Enums\Traits\HasLabels;

enum PaymentMethod: int
{
    use HasLabels;
    
    /**
     *  Επαγ. Λογαριασμός Πληρωμών Ημεδαπής
     */
    case METHOD_1 = 1;


    /**
     *  Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής
     */
    case METHOD_2 = 2;


    /**
     *  Μετρητά
     */
    case METHOD_3 = 3;


    /**
     *  Επιταγή
     */
    case METHOD_4 = 4;


    /**
     *  Επί Πιστώσει
     */
    case METHOD_5 = 5;

    /**
     * Web Banking
     */
    case METHOD_6 = 6;

    /**
     * POS / e-POS
     */
    case METHOD_7 = 7;


    /**
     * Άμεσες Πληρωμές IRIS
     */
    case METHOD_8 = 8;

    public function label(): string
    {
        return match ($this) {
            self::METHOD_1 => "Επαγ. Λογαριασμός Πληρωμών Ημεδαπής",
            self::METHOD_2 => "Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής",
            self::METHOD_3 => "Μετρητά",
            self::METHOD_4 => "Επιταγή",
            self::METHOD_5 => "Επί Πιστώσει",
            self::METHOD_6 => "Web Banking",
            self::METHOD_7 => "POS / e-POS",
            self::METHOD_8 => "Άμεσες Πληρωμές IRIS",
        };
    }
}
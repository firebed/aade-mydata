<?php

namespace Firebed\AadeMyData\Enums;

enum VatCategory: int
{
    use HasLabels;

    /**
     *  ΦΠΑ συντελεστής 24%
     */
    case VAT_1 = 1;


    /**
     *  ΦΠΑ συντελεστής 13%
     */
    case VAT_2 = 2;


    /**
     *  ΦΠΑ συντελεστής 6%
     */
    case VAT_3 = 3;


    /**
     *  ΦΠΑ συντελεστής 17%
     */
    case VAT_4 = 4;


    /**
     *  ΦΠΑ συντελεστής 9%
     */
    case VAT_5 = 5;


    /**
     *  ΦΠΑ συντελεστής 4%
     */
    case VAT_6 = 6;


    /**
     *  Άνευ Φ.Π.Α. 0%
     */
    case VAT_7 = 7;


    /**
     *  Εγγραφές χωρίς ΦΠΑ (πχ Μισθοδοσία, Αποσβέσεις)
     */
    case VAT_8 = 8;


    /**
     *  ΦΠΑ συντελεστής 3% (αρ.31 ν.5057/2023)
     */
    case VAT_9 = 9;


    /**
     *  ΦΠΑ συντελεστής 4% (αρ.31 ν.5057/2023)
     */
    case VAT_10 = 10;

    public function label(): string
    {
        return match ($this) {
            self::VAT_1 => "ΦΠΑ συντελεστής 24%",
            self::VAT_2 => "ΦΠΑ συντελεστής 13%",
            self::VAT_3 => "ΦΠΑ συντελεστής 6%",
            self::VAT_4 => "ΦΠΑ συντελεστής 17%",
            self::VAT_5 => "ΦΠΑ συντελεστής 9%",
            self::VAT_6 => "ΦΠΑ συντελεστής 4%",
            self::VAT_7 => "Άνευ Φ.Π.Α. 0%",
            self::VAT_8 => "Εγγραφές χωρίς ΦΠΑ",
            self::VAT_9 => "ΦΠΑ συντελεστής 3% (αρ.31 ν.5057/2023)",
            self::VAT_10 => "ΦΠΑ συντελεστής 4% (αρ.31 ν.5057/2023)",
        };
    }

    public function rate(): float
    {
        return match ($this) {
            self::VAT_1 => 24.0,
            self::VAT_2 => 13.0,
            self::VAT_3 => 6.0,
            self::VAT_4 => 17.0,
            self::VAT_5 => 9.0,
            self::VAT_6, self::VAT_10 => 4.0,
            self::VAT_7, self::VAT_8 => 0.0,
            self::VAT_9 => 3.0,
        };
    }

    public function isZero(): bool
    {
        return $this === self::VAT_7 || $this === self::VAT_8;
    }
    
    public function isExemption(): bool
    {
        return $this === self::VAT_7;
    }
}
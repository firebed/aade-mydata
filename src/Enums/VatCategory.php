<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum VatCategory: int
{
    use HasLabels;

    /**
     *  ΦΠΑ συντελεστής 24%
     */
    #[Label('ΦΠΑ συντελεστής 24%')]
    case RATE_24 = 1;

    /**
     *  ΦΠΑ συντελεστής 13%
     */
    #[Label('ΦΠΑ συντελεστής 13%')]
    case RATE_13 = 2;

    /**
     *  ΦΠΑ συντελεστής 6%
     */
    #[Label('ΦΠΑ συντελεστής 6%')]
    case RATE_6 = 3;

    /**
     *  ΦΠΑ συντελεστής 17%
     */
    #[Label('ΦΠΑ συντελεστής 17%')]
    case RATE_17 = 4;

    /**
     *  ΦΠΑ συντελεστής 9%
     */
    #[Label('ΦΠΑ συντελεστής 9%')]
    case RATE_9 = 5;

    /**
     *  ΦΠΑ συντελεστής 4%
     */
    #[Label('ΦΠΑ συντελεστής 4%')]
    case RATE_4 = 6;

    /**
     *  Άνευ Φ.Π.Α. 0%
     */
    #[Label('Άνευ Φ.Π.Α. 0%')]
    case EXEMPT = 7;

    /**
     *  Εγγραφές χωρίς ΦΠΑ (πχ Μισθοδοσία, Αποσβέσεις)
     */
    #[Label('Εγγραφές χωρίς ΦΠΑ')]
    case WITHOUT_VAT = 8;

    /**
     *  ΦΠΑ συντελεστής 3% (αρ.31 ν.5057/2023)
     */
    #[Label('ΦΠΑ συντελεστής 3% (αρ.31 ν.5057/2023)')]
    case RATE_3_ARTICLE_31 = 9;

    /**
     *  ΦΠΑ συντελεστής 4% (αρ.31 ν.5057/2023)
     */
    #[Label('ΦΠΑ συντελεστής 4% (αρ.31 ν.5057/2023)')]
    case RATE_4_ARTICLE_31 = 10;

    public function rate(): float
    {
        return match ($this) {
            self::RATE_24 => 24.0,
            self::RATE_13 => 13.0,
            self::RATE_6 => 6.0,
            self::RATE_17 => 17.0,
            self::RATE_9 => 9.0,
            self::RATE_4, self::RATE_4_ARTICLE_31 => 4.0,
            self::EXEMPT, self::WITHOUT_VAT => 0.0,
            self::RATE_3_ARTICLE_31 => 3.0,
        };
    }

    public function isZero(): bool
    {
        return $this === self::EXEMPT || $this === self::WITHOUT_VAT;
    }

    public function isExemption(): bool
    {
        return $this === self::EXEMPT;
    }
}

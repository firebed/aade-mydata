<?php

namespace Firebed\AadeMyData\Enums;

enum ReverseDeliveryNotePurpose: int
{
    /**
     * Μη Υπόχρεος Έκδοσης
     */
    case NOT_OBLIGED_TO_ISSUE = 1;

    /**
     * Άρνηση Έκδοσης / Εκ Παραδρομής Μη Έκδοση
     */
    case REFUSAL_OR_INADVERTENT_NON_ISSUANCE = 2;

    /**
     * Ενδοκοινοτική Απόκτηση
     */
    case INTRA_COMMUNITY_ACQUISITION = 3;

    /**
     * Απόκτηση Τρίτη Χώρα
     */
    case THIRD_COUNTRY_ACQUISITION = 4;

    /**
     * Αντίστροφη Υποχρέωση
     */
    case REVERSE_CHARGE = 5;

    public function label(): string
    {
        return match ($this) {
            self::NOT_OBLIGED_TO_ISSUE => "Μη Υπόχρεος Έκδοσης",
            self::REFUSAL_OR_INADVERTENT_NON_ISSUANCE => "Άρνηση Έκδοσης / Εκ Παραδρομής Μη Έκδοση",
            self::INTRA_COMMUNITY_ACQUISITION => "Ενδοκοινοτική Απόκτηση",
            self::THIRD_COUNTRY_ACQUISITION => "Απόκτηση Τρίτη Χώρα",
            self::REVERSE_CHARGE => "Αντίστροφη Υποχρέωση",
        };
    }
}

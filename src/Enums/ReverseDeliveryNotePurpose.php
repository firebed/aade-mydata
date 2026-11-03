<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum ReverseDeliveryNotePurpose: int
{
    use HasLabels;

    /**
     * Μη Υπόχρεος Έκδοσης
     */
    #[Label('Μη Υπόχρεος Έκδοσης')]
    case NOT_OBLIGED_TO_ISSUE = 1;

    /**
     * Άρνηση Έκδοσης / Εκ Παραδρομής Μη Έκδοση
     */
    #[Label('Άρνηση Έκδοσης / Εκ Παραδρομής Μη Έκδοση')]
    case REFUSAL_OR_INADVERTENT_NON_ISSUANCE = 2;

    /**
     * Ενδοκοινοτική Απόκτηση
     */
    #[Label('Ενδοκοινοτική Απόκτηση')]
    case INTRA_COMMUNITY_ACQUISITION = 3;

    /**
     * Απόκτηση Τρίτη Χώρα
     */
    #[Label('Απόκτηση Τρίτη Χώρα')]
    case THIRD_COUNTRY_ACQUISITION = 4;

    /**
     * Αντίστροφη Υποχρέωση
     */
    #[Label('Αντίστροφη Υποχρέωση')]
    case REVERSE_CHARGE = 5;
}

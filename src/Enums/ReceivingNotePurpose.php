<?php

namespace Firebed\AadeMyData\Enums;

/**
 * Αιτία Έκδοσης Δελτίου Ποσοτικής Παραλαβής (Δ.Π.Π.) - τύποι παραστατικών 10.1 / 10.2.
 *
 * @version 2.0.2
 */
enum ReceivingNotePurpose: int
{
    use HasLabels;

    /**
     * ΜΗ ΥΠΟΧΡΕΟΣ ΕΚΔΟΣΗΣ
     */
    case NOT_OBLIGED_TO_ISSUE = 1;

    /**
     * ΑΡΝΗΣΗ ΕΚΔΟΣΗΣ / ΕΚ ΠΑΡΑΔΡΟΜΗΣ ΜΗ ΕΚΔΟΣΗ
     */
    case REFUSAL_OR_INADVERTENT_NON_ISSUANCE = 2;

    /**
     * ΕΝΔΟΚΟΙΝΟΤΙΚΗ ΑΠΟΚΤΗΣΗ
     */
    case INTRA_COMMUNITY_ACQUISITION = 3;

    /**
     * ΑΠΟΚΤΗΣΗ ΤΡΙΤΗ ΧΩΡΑ
     */
    case THIRD_COUNTRY_ACQUISITION = 4;

    /**
     * ΠΟΣΟΤΙΚΟΣ ΕΛΕΓΧΟΣ (μόνο για τον τύπο 10.1)
     */
    case QUANTITATIVE_CONTROL = 5;

    /**
     * ΜΗ / ΜΕΡΙΚΗ ΠΑΡΑΔΟΣΗ
     */
    case NON_OR_PARTIAL_DELIVERY = 6;

    /**
     * ΛΟΙΠΕΣ ΠΕΡΙΠΤΩΣΕΙΣ
     */
    case OTHER_CASES = 7;

    public function label(): string
    {
        return match ($this) {
            self::NOT_OBLIGED_TO_ISSUE => 'ΜΗ ΥΠΟΧΡΕΟΣ ΕΚΔΟΣΗΣ',
            self::REFUSAL_OR_INADVERTENT_NON_ISSUANCE => 'ΑΡΝΗΣΗ ΕΚΔΟΣΗΣ / ΕΚ ΠΑΡΑΔΡΟΜΗΣ ΜΗ ΕΚΔΟΣΗ',
            self::INTRA_COMMUNITY_ACQUISITION => 'ΕΝΔΟΚΟΙΝΟΤΙΚΗ ΑΠΟΚΤΗΣΗ',
            self::THIRD_COUNTRY_ACQUISITION => 'ΑΠΟΚΤΗΣΗ ΤΡΙΤΗ ΧΩΡΑ',
            self::QUANTITATIVE_CONTROL => 'ΠΟΣΟΤΙΚΟΣ ΕΛΕΓΧΟΣ',
            self::NON_OR_PARTIAL_DELIVERY => 'ΜΗ / ΜΕΡΙΚΗ ΠΑΡΑΔΟΣΗ',
            self::OTHER_CASES => 'ΛΟΙΠΕΣ ΠΕΡΙΠΤΩΣΕΙΣ',
        };
    }
}

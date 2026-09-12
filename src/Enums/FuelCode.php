<?php

namespace Firebed\AadeMyData\Enums;

enum FuelCode: int
{
    use HasLabels;

    /**
     *  Benzine 95RON
     */
    case TYPE_10 = 10;


    /**
     *  Benzine 95RON+
     */
    case TYPE_11 = 11;


    /**
     *  Benzine 100RON
     */
    case TYPE_12 = 12;


    /**
     *  Benzine LRP
     */
    case TYPE_13 = 13;


    /**
     *  Βενζίνη αεροπλάνων
     */
    case TYPE_14 = 14;


    /**
     *  Ειδικό καύσιμο αεριωθουμένων
     */
    case TYPE_15 = 15;


    /**
     *  Diesel
     */
    case TYPE_20 = 20;


    /**
     *  Diesel premium
     */
    case TYPE_21 = 21;


    /**
     *  Diesel Heating
     */
    case TYPE_30 = 30;


    /**
     *  Diesel Heating premium
     */
    case TYPE_31 = 31;


    /**
     *  Diesel Light
     */
    case TYPE_32 = 32;


    /**
     *  Diesel άλλων χρήσεων
     */
    case TYPE_33 = 33;


    /**
     *  Diesel ναυτιλίας
     */
    case TYPE_34 = 34;


    /**
     *  Κηροζίνη JP1
     */
    case TYPE_35 = 35;


    /**
     *  Κηροζίνη άλλων χρήσεων
     */
    case TYPE_36 = 36;


    /**
     *  Μαζούτ
     */
    case TYPE_37 = 37;


    /**
     *  Μαζούτ ναυτιλίας
     */
    case TYPE_38 = 38;


    /**
     *  LPG (υγραέριο)
     */
    case TYPE_40 = 40;


    /**
     *  Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (χύδην)
     */
    case TYPE_41 = 41;


    /**
     *  Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (χύδην)
     */
    case TYPE_42 = 42;


    /**
     *  Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (σε φιάλες)
     */
    case TYPE_43 = 43;


    /**
     *  Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (σε φιάλες)
     */
    case TYPE_44 = 44;


    /**
     *  CNG (πεπιεσμένο φυσικό αέριο)
     */
    case TYPE_50 = 50;


    /**
     *  Αρωματικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2707
     */
    case TYPE_60 = 60;


    /**
     *  Κυκλικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2902
     */
    case TYPE_61 = 61;


    /**
     *  Ελαφρύ πετρέλαιο (WHITE SPIRIT)
     */
    case TYPE_70 = 70;


    /**
     *  Ελαφριά λάδια
     */
    case TYPE_71 = 71;


    /**
     *  Βιοντίζελ
     */
    case TYPE_72 = 72;


    /**
     * Λοιπές χρεώσεις υπηρεσιών. Χρησιμοποιείται στις περιπτώσεις
     * που σε ένα παραστατικό εκτός από καύσιμα υπάρχει η ανάγκη να
     * τιμολογούνται και λοιπές χρεώσεις μικρών ποσών.
     */
    case TYPE_999 = 999;

    public function label(): string
    {
        return match ($this) {
            self::TYPE_10 => "Benzine 95RON",
            self::TYPE_11 => "Benzine 95RON+",
            self::TYPE_12 => "Benzine 100RON",
            self::TYPE_13 => "Benzine LRP",
            self::TYPE_14 => "Βενζίνη αεροπλάνων",
            self::TYPE_15 => "Ειδικό καύσιμο αεριωθουμένων",
            self::TYPE_20 => "Diesel",
            self::TYPE_21 => "Diesel premium",
            self::TYPE_30 => "Diesel Heating",
            self::TYPE_31 => "Diesel Heating premium",
            self::TYPE_32 => "Diesel Light",
            self::TYPE_33 => "Diesel άλλων χρήσεων",
            self::TYPE_34 => "Diesel ναυτιλίας",
            self::TYPE_35 => "Κηροζίνη JP1",
            self::TYPE_36 => "Κηροζίνη άλλων χρήσεων",
            self::TYPE_37 => "Μαζούτ",
            self::TYPE_38 => "Μαζούτ ναυτιλίας",
            self::TYPE_40 => "LPG (υγραέριο)",
            self::TYPE_41 => "Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (χύδην)",
            self::TYPE_42 => "Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (χύδην)",
            self::TYPE_43 => "Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (σε φιάλες)",
            self::TYPE_44 => "Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (σε φιάλες)",
            self::TYPE_50 => "CNG (πεπιεσμένο φυσικό αέριο)",
            self::TYPE_60 => "Αρωματικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2707",
            self::TYPE_61 => "Κυκλικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2902",
            self::TYPE_70 => "Ελαφρύ πετρέλαιο (WHITE SPIRIT)",
            self::TYPE_71 => "Ελαφριά λάδια",
            self::TYPE_72 => "Βιοντίζελ",
            self::TYPE_999 => "Λοιπές χρεώσεις υπηρεσιών",
        };
    }
}
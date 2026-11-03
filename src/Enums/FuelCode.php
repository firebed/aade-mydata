<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum FuelCode: int
{
    use HasLabels;

    /**
     *  Benzine 95RON
     */
    #[Label('Benzine 95RON')]
    case BENZINE_95_RON = 10;

    /**
     *  Benzine 95RON+
     */
    #[Label('Benzine 95RON+')]
    case BENZINE_95_RON_PLUS = 11;

    /**
     *  Benzine 100RON
     */
    #[Label('Benzine 100RON')]
    case BENZINE_100_RON = 12;

    /**
     *  Benzine LRP
     */
    #[Label('Benzine LRP')]
    case BENZINE_LRP = 13;

    /**
     *  Βενζίνη αεροπλάνων
     */
    #[Label('Βενζίνη αεροπλάνων')]
    case AVIATION_GASOLINE = 14;

    /**
     *  Ειδικό καύσιμο αεριωθουμένων
     */
    #[Label('Ειδικό καύσιμο αεριωθουμένων')]
    case JET_FUEL = 15;

    /**
     *  Diesel
     */
    #[Label('Diesel')]
    case DIESEL = 20;

    /**
     *  Diesel premium
     */
    #[Label('Diesel premium')]
    case DIESEL_PREMIUM = 21;

    /**
     *  Diesel Heating
     */
    #[Label('Diesel Heating')]
    case DIESEL_HEATING = 30;

    /**
     *  Diesel Heating premium
     */
    #[Label('Diesel Heating premium')]
    case DIESEL_HEATING_PREMIUM = 31;

    /**
     *  Diesel Light
     */
    #[Label('Diesel Light')]
    case DIESEL_LIGHT = 32;

    /**
     *  Diesel άλλων χρήσεων
     */
    #[Label('Diesel άλλων χρήσεων')]
    case DIESEL_OTHER_USES = 33;

    /**
     *  Diesel ναυτιλίας
     */
    #[Label('Diesel ναυτιλίας')]
    case DIESEL_MARINE = 34;

    /**
     *  Κηροζίνη JP1
     */
    #[Label('Κηροζίνη JP1')]
    case KEROSENE_JP1 = 35;

    /**
     *  Κηροζίνη άλλων χρήσεων
     */
    #[Label('Κηροζίνη άλλων χρήσεων')]
    case KEROSENE_OTHER_USES = 36;

    /**
     *  Μαζούτ
     */
    #[Label('Μαζούτ')]
    case FUEL_OIL = 37;

    /**
     *  Μαζούτ ναυτιλίας
     */
    #[Label('Μαζούτ ναυτιλίας')]
    case FUEL_OIL_MARINE = 38;

    /**
     *  LPG (υγραέριο)
     */
    #[Label('LPG (υγραέριο)')]
    case LPG = 40;

    /**
     *  Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (χύδην)
     */
    #[Label('Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (χύδην)')]
    case LPG_METHANE_ENGINES_BULK = 41;

    /**
     *  Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (χύδην)
     */
    #[Label('Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (χύδην)')]
    case LPG_METHANE_HEATING_BULK = 42;

    /**
     *  Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (σε φιάλες)
     */
    #[Label('Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (σε φιάλες)')]
    case LPG_METHANE_ENGINES_BOTTLED = 43;

    /**
     *  Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (σε φιάλες)
     */
    #[Label('Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (σε φιάλες)')]
    case LPG_METHANE_HEATING_BOTTLED = 44;

    /**
     *  CNG (πεπιεσμένο φυσικό αέριο)
     */
    #[Label('CNG (πεπιεσμένο φυσικό αέριο)')]
    case CNG = 50;

    /**
     *  Αρωματικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2707
     */
    #[Label('Αρωματικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2707')]
    case AROMATIC_HYDROCARBONS_2707 = 60;

    /**
     *  Κυκλικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2902
     */
    #[Label('Κυκλικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2902')]
    case CYCLIC_HYDROCARBONS_2902 = 61;

    /**
     *  Ελαφρύ πετρέλαιο (WHITE SPIRIT)
     */
    #[Label('Ελαφρύ πετρέλαιο (WHITE SPIRIT)')]
    case WHITE_SPIRIT = 70;

    /**
     *  Ελαφριά λάδια
     */
    #[Label('Ελαφριά λάδια')]
    case LIGHT_OILS = 71;

    /**
     *  Βιοντίζελ
     */
    #[Label('Βιοντίζελ')]
    case BIODIESEL = 72;

    /**
     * Λοιπές χρεώσεις υπηρεσιών. Χρησιμοποιείται στις περιπτώσεις
     * που σε ένα παραστατικό εκτός από καύσιμα υπάρχει η ανάγκη να
     * τιμολογούνται και λοιπές χρεώσεις μικρών ποσών.
     */
    #[Label('Λοιπές χρεώσεις υπηρεσιών')]
    case OTHER_SERVICE_CHARGES = 999;
}

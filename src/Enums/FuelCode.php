<?php

namespace Firebed\AadeMyData\Enums;

enum FuelCode: string
{
    /**
     *  Benzine 95RON
     */
    case TYPE_10 = "10";


    /**
     *  Benzine 95RON+
     */
    case TYPE_11 = "11";


    /**
     *  Benzine 100RON
     */
    case TYPE_12 = "12";


    /**
     *  Benzine LRP
     */
    case TYPE_13 = "13";


    /**
     *  Diesel
     */
    case TYPE_20 = "20";


    /**
     *  Diesel premium
     */
    case TYPE_21 = "21";


    /**
     *  Diesel Heatnn
     */
    case TYPE_30 = "30";


    /**
     *  Diesel Heat premium
     */
    case TYPE_31 = "31";


    /**
     *  Diesel Linht
     */
    case TYPE_32 = "32";


    /**
     *  LPG (υγραέριο)
     */
    case TYPE_40 = "40";


    /**
     *  Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (χύδην)
     */
    case TYPE_41 = "41";

    
    /**
     *  Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (χύδην)
     */
    case TYPE_42 = "42";

    
    /**
     *  Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (σε φιάλες)
     */
    case TYPE_43 = "43";

    
    /**
     *  Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (σε φιάλες)
     */
    case TYPE_44 = "44";

    
    /**
     *  CNG (πεπιεσμένο φυσικό αέριο)
     */
    case TYPE_50 = "50";


    /**
     *  Αρωματικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2707
     */
    case TYPE_60 = "60";

    
    /**
     *  Κυκλικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2902
     */
    case TYPE_61 = "61";

    
    /**
     *  Ελαφρύ πετρέλαιο (WHITE SPIRIT)
     */
    case TYPE_70 = "70";

    
    /**
     *  Ελαφριά λάδια
     */
    case TYPE_71 = "71";

    
    /**
     *  Βιοντίζελ
     */
    case TYPE_72 = "72";
}
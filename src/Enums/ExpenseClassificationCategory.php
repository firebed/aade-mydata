<?php

namespace Firebed\AadeMyData\Enums;

enum ExpenseClassificationCategory: string
{
    /**
     *  Αγορές Εμπορευμάτων (-) / (+)
     */
    case CATEGORY_2_1 = "category2_1";


    /**
     *  Αγορές Α'-Β' Υλών (-) / (+)
     */
    case CATEGORY_2_2 = "category2_2";


    /**
     *  Λήψη Υπηρεσιών (-) / (+)
     */
    case CATEGORY_2_3 = "category2_3";


    /**
     *  Γενικά Έξοδα με δικαίωμα έκπτωσης ΦΠΑ (-) / (+)
     */
    case CATEGORY_2_4 = "category2_4";


    /**
     *  Γενικά Έξοδα χωρίς δικαίωμα έκπτωσης ΦΠΑ (-) / (+)
     */
    case CATEGORY_2_5 = "category2_5";


    /**
     *  Αμοιβές και Παροχές προσωπικού (-) / (+)
     */
    case CATEGORY_2_6 = "category2_6";


    /**
     *  Αγορές Παγίων (-) / (+)
     */
    case CATEGORY_2_7 = "category2_7";


    /**
     *  Αποσβέσεις Παγίων (-) / (+)
     */
    case CATEGORY_2_8 = "category2_8";


    /**
     *  Έξοδα για λ/σμο τρίτων (-) / (+)
     */
    case CATEGORY_2_9 = "category2_9";


    /**
     *  Έξοδα προηγούμενων χρήσεων (-) / (+)
     */
    case CATEGORY_2_10 = "category2_10";


    /**
     *  Έξοδα επομένων χρήσεων (-) / (+)
     */
    case CATEGORY_2_11 = "category2_11";


    /**
     *  Λοιπές Εγγραφές Τακτοποίησης Εξόδων (-) / (+)
     */
    case CATEGORY_2_12 = "category2_12";


    /**
     *  Αποθέματα Έναρξης Περιόδου (-) / (+)
     */
    case CATEGORY_2_13 = "category2_13";


    /**
     *  Αποθέματα Λήξης Περιόδου (-) / (+)
     */
    case CATEGORY_2_14 = "category2_14";


    /**
     *  Λοιπά Πληροφοριακά Στοιχεία Εξόδων (-) / (+)
     */
    case CATEGORY_2_95 = "category2_95";
}
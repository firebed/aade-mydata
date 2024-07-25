<?php

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Services\CategoryClassificationCollection;
use Firebed\AadeMyData\Services\Classifications;

enum ExpenseClassificationCategory: string
{
    use HasLabels;

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

    public function label(): string
    {
        return match ($this) {
            self::CATEGORY_2_1 => "Αγορές Εμπορευμάτων (-) / (+)",
            self::CATEGORY_2_2 => "Αγορές Α'-Β' Υλών (-) / (+)",
            self::CATEGORY_2_3 => "Λήψη Υπηρεσιών (-) / (+)",
            self::CATEGORY_2_4 => "Γενικά Έξοδα με δικαίωμα έκπτωσης ΦΠΑ (-) / (+)",
            self::CATEGORY_2_5 => "Γενικά Έξοδα χωρίς δικαίωμα έκπτωσης ΦΠΑ (-) / (+)",
            self::CATEGORY_2_6 => "Αμοιβές και Παροχές προσωπικού (-) / (+)",
            self::CATEGORY_2_7 => "Αγορές Παγίων (-) / (+)",
            self::CATEGORY_2_8 => "Αποσβέσεις Παγίων (-) / (+)",
            self::CATEGORY_2_9 => "Έξοδα για λ/σμο τρίτων (-) / (+)",
            self::CATEGORY_2_10 => "Έξοδα προηγούμενων χρήσεων (-) / (+)",
            self::CATEGORY_2_11 => "Έξοδα επομένων χρήσεων (-) / (+)",
            self::CATEGORY_2_12 => "Λοιπές Εγγραφές Τακτοποίησης Εξόδων (-) / (+)",
            self::CATEGORY_2_13 => "Αποθέματα Έναρξης Περιόδου (-) / (+)",
            self::CATEGORY_2_14 => "Αποθέματα Λήξης Περιόδου (-) / (+)",
            self::CATEGORY_2_95 => "Λοιπά Πληροφοριακά Στοιχεία Εξόδων (-) / (+)",
        };
    }

    public function for(InvoiceType $type): CategoryClassificationCollection
    {
        return Classifications::expenseClassifications($type, $this);
    }
}
<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Services\CategoryClassificationCollection;
use Firebed\AadeMyData\Services\Classifications;
use Firebed\AadeMyData\Services\TypeClassificationCollection;

enum ExpenseClassificationCategory: string
{
    use HasLabels;

    /**
     *  Αγορές Εμπορευμάτων (-) / (+)
     */
    #[Label('Αγορές Εμπορευμάτων (-) / (+)')]
    case CATEGORY_2_1 = 'category2_1';

    /**
     *  Αγορές Α'-Β' Υλών (-) / (+)
     */
    #[Label('Αγορές Α\'-Β\' Υλών (-) / (+)')]
    case CATEGORY_2_2 = 'category2_2';

    /**
     *  Λήψη Υπηρεσιών (-) / (+)
     */
    #[Label('Λήψη Υπηρεσιών (-) / (+)')]
    case CATEGORY_2_3 = 'category2_3';

    /**
     *  Γενικά Έξοδα με δικαίωμα έκπτωσης ΦΠΑ (-) / (+)
     */
    #[Label('Γενικά Έξοδα με δικαίωμα έκπτωσης ΦΠΑ (-) / (+)')]
    case CATEGORY_2_4 = 'category2_4';

    /**
     *  Γενικά Έξοδα χωρίς δικαίωμα έκπτωσης ΦΠΑ (-) / (+)
     */
    #[Label('Γενικά Έξοδα χωρίς δικαίωμα έκπτωσης ΦΠΑ (-) / (+)')]
    case CATEGORY_2_5 = 'category2_5';

    /**
     *  Αμοιβές και Παροχές προσωπικού (-) / (+)
     */
    #[Label('Αμοιβές και Παροχές προσωπικού (-) / (+)')]
    case CATEGORY_2_6 = 'category2_6';

    /**
     *  Αγορές Παγίων (-) / (+)
     */
    #[Label('Αγορές Παγίων (-) / (+)')]
    case CATEGORY_2_7 = 'category2_7';

    /**
     *  Αποσβέσεις Παγίων (-) / (+)
     */
    #[Label('Αποσβέσεις Παγίων (-) / (+)')]
    case CATEGORY_2_8 = 'category2_8';

    /**
     *  Έξοδα για λ/σμο τρίτων (-) / (+)
     */
    #[Label('Έξοδα για λ/σμο τρίτων (-) / (+)')]
    case CATEGORY_2_9 = 'category2_9';

    /**
     *  Έξοδα προηγούμενων χρήσεων (-) / (+)
     */
    #[Label('Έξοδα προηγούμενων χρήσεων (-) / (+)')]
    case CATEGORY_2_10 = 'category2_10';

    /**
     *  Έξοδα επομένων χρήσεων (-) / (+)
     */
    #[Label('Έξοδα επομένων χρήσεων (-) / (+)')]
    case CATEGORY_2_11 = 'category2_11';

    /**
     *  Λοιπές Εγγραφές Τακτοποίησης Εξόδων (-) / (+)
     */
    #[Label('Λοιπές Εγγραφές Τακτοποίησης Εξόδων (-) / (+)')]
    case CATEGORY_2_12 = 'category2_12';

    /**
     *  Αποθέματα Έναρξης Περιόδου (-) / (+)
     */
    #[Label('Αποθέματα Έναρξης Περιόδου (-) / (+)')]
    case CATEGORY_2_13 = 'category2_13';

    /**
     *  Αποθέματα Λήξης Περιόδου (-) / (+)
     */
    #[Label('Αποθέματα Λήξης Περιόδου (-) / (+)')]
    case CATEGORY_2_14 = 'category2_14';

    /**
     *  Λοιπά Πληροφοριακά Στοιχεία Εξόδων (-) / (+)
     */
    #[Label('Λοιπά Πληροφοριακά Στοιχεία Εξόδων (-) / (+)')]
    case CATEGORY_2_95 = 'category2_95';

    public function for(InvoiceType $type): CategoryClassificationCollection|TypeClassificationCollection
    {
        return Classifications::expenseClassifications($type, $this);
    }
}

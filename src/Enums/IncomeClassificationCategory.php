<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Services\CategoryClassificationCollection;
use Firebed\AadeMyData\Services\Classifications;
use Firebed\AadeMyData\Services\TypeClassificationCollection;

enum IncomeClassificationCategory: string
{
    use HasLabels;

    /**
     * Έσοδα από Πώληση Εμπορευμάτων (+) / (-)
     */
    #[Label('Έσοδα από Πώληση Εμπορευμάτων')]
    case CATEGORY_1_1 = 'category1_1';

    /**
     * Έσοδα από Πώληση Προϊόντων (+) / (-)
     */
    #[Label('Έσοδα από Πώληση Προϊόντων')]
    case CATEGORY_1_2 = 'category1_2';

    /**
     * Έσοδα από Παροχή Υπηρεσιών (+) / (-)
     */
    #[Label('Έσοδα από Παροχή Υπηρεσιών')]
    case CATEGORY_1_3 = 'category1_3';

    /**
     * Έσοδα από Πώληση Παγίων (+) / (-)
     */
    #[Label('Έσοδα από Πώληση Παγίων')]
    case CATEGORY_1_4 = 'category1_4';

    /**
     * Λοιπά Έσοδα / Κέρδη (+) / (-)
     */
    #[Label('Λοιπά Έσοδα / Κέρδη')]
    case CATEGORY_1_5 = 'category1_5';

    /**
     * Αυτοπαραδόσεις / Ιδιοχρησιμοποιήσεις
     */
    #[Label('Αυτοπαραδόσεις / Ιδιοχρησιμοποιήσεις')]
    case CATEGORY_1_6 = 'category1_6';

    /**
     * Έσοδα για λογαριασμό τρίτων
     */
    #[Label('Έσοδα για λογαριασμό τρίτων')]
    case CATEGORY_1_7 = 'category1_7';

    /**
     * Έσοδα προηγούμενων χρήσεων
     */
    #[Label('Έσοδα προηγούμενων χρήσεων')]
    case CATEGORY_1_8 = 'category1_8';

    /**
     * Έσοδα επομένων χρήσεων
     */
    #[Label('Έσοδα επομένων χρήσεων')]
    case CATEGORY_1_9 = 'category1_9';

    /**
     * Λοιπές Εγγραφές Τακτοποίησης Εσόδων
     */
    #[Label('Λοιπές Εγγραφές Τακτοποίησης Εσόδων')]
    case CATEGORY_1_10 = 'category1_10';

    /**
     * Λοιπά Πληροφοριακά Στοιχεία Εσόδων
     */
    #[Label('Λοιπά Πληροφοριακά Στοιχεία Εσόδων')]
    case CATEGORY_1_95 = 'category1_95';

    /**
     * Διακίνηση
     */
    #[Label('Διακίνηση')]
    case CATEGORY_3 = 'category3';

    public function for(InvoiceType $type): CategoryClassificationCollection|TypeClassificationCollection
    {
        return Classifications::incomeClassifications($type, $this);
    }
}

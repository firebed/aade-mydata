<?php

namespace Firebed\AadeMyData\Enums;

use Firebed\AadeMyData\Services\CategoryClassificationCollection;
use Firebed\AadeMyData\Services\Classifications;

enum IncomeClassificationCategory: string
{
    use HasLabels;
    
    /**
     * Έσοδα από Πώληση Εμπορευμάτων (+) / (-)
     */
    case CATEGORY_1_1 = "category1_1";


    /**
     * Έσοδα από Πώληση Προϊόντων (+) / (-)
     */
    case CATEGORY_1_2 = "category1_2";


    /**
     * Έσοδα από Παροχή Υπηρεσιών (+) / (-)
     */
    case CATEGORY_1_3 = "category1_3";


    /**
     * Έσοδα από Πώληση Παγίων (+) / (-)
     */
    case CATEGORY_1_4 = "category1_4";


    /**
     * Λοιπά Έσοδα / Κέρδη (+) / (-)
     */
    case CATEGORY_1_5 = "category1_5";


    /**
     * Αυτοπαραδόσεις / Ιδιοχρησιμοποιήσεις
     */
    case CATEGORY_1_6 = "category1_6";


    /**
     * Έσοδα για λογαριασμό τρίτων
     */
    case CATEGORY_1_7 = "category1_7";


    /**
     * Έσοδα προηγούμενων χρήσεων
     */
    case CATEGORY_1_8 = "category1_8";


    /**
     * Έσοδα επομένων χρήσεων
     */
    case CATEGORY_1_9 = "category1_9";


    /**
     * Λοιπές Εγγραφές Τακτοποίησης Εσόδων
     */
    case CATEGORY_1_10 = "category1_10";


    /**
     * Λοιπά Πληροφοριακά Στοιχεία Εσόδων
     */
    case CATEGORY_1_95 = "category1_95";


    /**
     * Διακίνηση
     */
    case CATEGORY_3 = 'category3';

    public function label(): string
    {
        return match ($this) {
            self::CATEGORY_1_1  => "Έσοδα από Πώληση Εμπορευμάτων",
            self::CATEGORY_1_2  => "Έσοδα από Πώληση Προϊόντων",
            self::CATEGORY_1_3  => "Έσοδα από Παροχή Υπηρεσιών",
            self::CATEGORY_1_4  => "Έσοδα από Πώληση Παγίων",
            self::CATEGORY_1_5  => "Λοιπά Έσοδα / Κέρδη",
            self::CATEGORY_1_6  => "Αυτοπαραδόσεις / Ιδιοχρησιμοποιήσεις",
            self::CATEGORY_1_7  => "Έσοδα για λογαριασμό τρίτων",
            self::CATEGORY_1_8  => "Έσοδα προηγούμενων χρήσεων",
            self::CATEGORY_1_9  => "Έσοδα επομένων χρήσεων",
            self::CATEGORY_1_10 => "Λοιπές Εγγραφές Τακτοποίησης Εσόδων",
            self::CATEGORY_1_95 => "Λοιπά Πληροφοριακά Στοιχεία Εσόδων",
            self::CATEGORY_3    => "Διακίνηση",
        };
    }

    public function for(InvoiceType $type): CategoryClassificationCollection
    {
        return Classifications::incomeClassifications($type, $this);
    }
}
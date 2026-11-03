<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum SpecialInvoiceCategory: int
{
    use HasLabels;

    /**
     *  Επιδοτήσεις – Επιχορηγήσεις
     */
    #[Label('Επιδοτήσεις – Επιχορηγήσεις')]
    case SUBSIDIES_GRANTS = 1;

    /**
     *  Έσοδα Λιανικής Ξενοδοχείων
     */
    #[Label('Έσοδα Λιανικής Ξενοδοχείων')]
    case HOTEL_RETAIL_INCOME = 2;

    /**
     *  Λογιστική Εγγραφή
     */
    #[Label('Λογιστική Εγγραφή')]
    case ACCOUNTING_ENTRY = 3;

    /**
     * Tax Free
     * Έγκυρη τιμή μόνο για διαβίβαση μέσω erp ή έκδοση μέσω παρόχου ή timologio
     */
    #[Label('Tax Free')]
    case TAX_FREE = 4;

    /**
     * Σύνθετες συναλλαγές ημεδαπής – αλλοδαπής
     *
     * @version 1.0.7
     */
    #[Label('Σύνθετες συναλλαγές ημεδαπής – αλλοδαπής')]
    case COMPLEX_DOMESTIC_FOREIGN_TRANSACTIONS = 5;

    /**
     * Δικαιούχοι του άρθρου 3 της υπό στοιχεία 139818 ΕΞ2022/28.09.2022 (Β’5083) κοινής υπουργικής απόφασης
     *
     * @version 1.0.7
     */
    #[Label('Δικαιούχοι του άρθρου 3 της υπό στοιχεία 139818 ΕΞ2022/28.09.2022 (Β’5083) κοινής υπουργικής απόφασης')]
    case DECISION_139818_BENEFICIARIES = 6;

    /**
     * Αγορά αγροτικών αγαθών υπηρεσιών Άρθρο 41 του Κώδικα ΦΠΑ
     *
     * @version 1.0.7
     */
    #[Label('Αγορά αγροτικών αγαθών υπηρεσιών Άρθρο 41 του Κώδικα ΦΠΑ')]
    case AGRICULTURAL_PURCHASES_ARTICLE_41 = 7;

    /**
     * Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_1
     * Μόνο για ανάγνωση – μη έγκυρη τιμή για αποστολή μέσω ERP / Πάροχο
     *
     * @version 1.0.7
     */
    #[Label('Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_1')]
    case RETAIL_INCOME_FISCAL_DEVICE_AADE_1 = 8;

    /**
     * Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_2
     * Μόνο για ανάγνωση – μη έγκυρη τιμή για αποστολή μέσω ERP / Πάροχο
     *
     * @version 1.0.7
     */
    #[Label('Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_2')]
    case RETAIL_INCOME_FISCAL_DEVICE_AADE_2 = 9;

    /**
     * Έσοδα Λιανικών ΦΗΜ Επιχείρησης Απόκλιση
     *
     * @version 1.0.7
     */
    #[Label('Έσοδα Λιανικών ΦΗΜ Επιχείρησης Απόκλιση')]
    case RETAIL_INCOME_FISCAL_DEVICE_DEVIATION = 10;

    /**
     *  Επίδομα Θέρμανσης
     */
    #[Label('Επίδομα Θέρμανσης')]
    case HEATING_ALLOWANCE = 11;

    /**
     *  Συναλλαγές εστίασης
     */
    #[Label('Συναλλαγές εστίασης')]
    case RESTAURANT_TRANSACTIONS = 12;

    /**
     * Ένδειξη Δυσχέρεια Συσχέτισης
     * Έγκυρη τιμή μόνο για τύπους παραστατικών 11.4 και 14.30
     *
     * @version 1.0.11
     */
    #[Label('Ένδειξη Δυσχέρεια Συσχέτισης')]
    case CORRELATION_DIFFICULTY = 13;
}

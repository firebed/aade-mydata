<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum MovePurpose: int
{
    use HasLabels;

    /**
     *  Πώληση
     */
    #[Label('Πώληση')]
    case SALE = 1;

    /**
     *  Πώληση για Λογαριασμό Τρίτων
     */
    #[Label('Πώληση για Λογαριασμό Τρίτων')]
    case SALE_FOR_THIRD_PARTIES = 2;

    /**
     *  Δειγματισμός
     */
    #[Label('Δειγματισμός')]
    case SAMPLING = 3;

    /**
     *  Έκθεση
     */
    #[Label('Έκθεση')]
    case EXHIBITION = 4;

    /**
     *  Επιστροφή
     */
    #[Label('Επιστροφή')]
    case RETURN = 5;

    /**
     *  Φύλαξη
     */
    #[Label('Φύλαξη')]
    case SAFEKEEPING = 6;

    /**
     *  Επεξεργασία - Συναρμολόγηση - Αποσυναρμολόγηση
     */
    #[Label('Επεξεργασία - Συναρμολόγηση - Αποσυναρμολόγηση')]
    case PROCESSING_ASSEMBLY_DISASSEMBLY = 7;

    /**
     *  Ενδοδιακίνηση
     */
    #[Label('Ενδοδιακίνηση')]
    case INTERNAL_MOVEMENT = 8;

    /**
     *  Αγορά
     */
    #[Label('Αγορά')]
    case PURCHASE = 9;

    /**
     *  Εφοδιασμός πλοίων και αεροσκαφών
     */
    #[Label('Εφοδιασμός πλοίων και αεροσκαφών')]
    case SHIP_AND_AIRCRAFT_SUPPLY = 10;

    /**
     *  Δωρεάν διάθεση
     */
    #[Label('Δωρεάν διάθεση')]
    case FREE_DISPOSAL = 11;

    /**
     *  Εγγύηση
     */
    #[Label('Εγγύηση')]
    case WARRANTY = 12;

    /**
     *  Χρησιδανεισμός
     */
    #[Label('Χρησιδανεισμός')]
    case LOAN_FOR_USE = 13;

    /**
     *  Αποθήκευση σε Τρίτους
     */
    #[Label('Αποθήκευση σε Τρίτους')]
    case STORAGE_AT_THIRD_PARTIES = 14;

    /**
     *  Επιστροφή από Φύλαξη
     */
    #[Label('Επιστροφή από Φύλαξη')]
    case RETURN_FROM_SAFEKEEPING = 15;

    /**
     *  Ανακύκλωση
     */
    #[Label('Ανακύκλωση')]
    case RECYCLING = 16;

    /**
     *  Καταστροφή άχρηστου υλικού
     */
    #[Label('Καταστροφή άχρηστου υλικού')]
    case SCRAP_DESTRUCTION = 17;

    /**
     *  Διακίνηση Παγίων (Ενδοδιακίνηση)
     */
    #[Label('Διακίνηση Παγίων (Ενδοδιακίνηση)')]
    case FIXED_ASSETS_MOVEMENT = 18;

    /**
     *  Λοιπές Διακινήσεις
     */
    #[Label('Λοιπές Διακινήσεις')]
    case OTHER_MOVEMENTS = 19;

    /**
     * Μεταφορές - Ταχυμεταφορές
     */
    #[Label('Μεταφορές - Ταχυμεταφορές')]
    case TRANSPORT_AND_COURIER = 20;
}

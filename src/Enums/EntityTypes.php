<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum EntityTypes: int
{
    use HasLabels;

    /**
     * Φορολογικός Εκπρόσωπος
     */
    #[Label('Φορολογικός Εκπρόσωπος')]
    case TAX_REPRESENTATIVE = 1;

    /**
     * Διαμεσολαβητής
     */
    #[Label('Διαμεσολαβητής')]
    case INTERMEDIARY = 2;

    /**
     * Μεταφορέας
     */
    #[Label('Μεταφορέας')]
    case CARRIER = 3;

    /**
     * Λήπτης του Αποστολέα (Πωλητή)
     */
    #[Label('Λήπτης του Αποστολέα (Πωλητή)')]
    case RECIPIENT_OF_SENDER = 4;

    /**
     * Αποστολέας (Πωλητής)
     */
    #[Label('Αποστολέας (Πωλητής)')]
    case SENDER = 5;

    /**
     * Λοιπές Συσχετιζόμενες Οντότητες
     */
    #[Label('Λοιπές Συσχετιζόμενες Οντότητες')]
    case OTHER_RELATED_ENTITIES = 6;
}

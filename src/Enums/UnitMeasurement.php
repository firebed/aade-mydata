<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum UnitMeasurement: int
{
    use HasLabels;

    /**
     *  Τεμάχια
     */
    #[Label('Τεμάχια')]
    case PIECES = 1;

    /**
     *  Κιλά
     */
    #[Label('Κιλά')]
    case KILOGRAMS = 2;

    /**
     *  Λίτρα
     */
    #[Label('Λίτρα')]
    case LITERS = 3;

    /**
     * Μέτρα
     */
    #[Label('Μέτρα')]
    case METERS = 4;

    /**
     * Τετραγωνικά Μέτρα
     */
    #[Label('Τετραγωνικά Μέτρα')]
    case SQUARE_METERS = 5;

    /**
     * Κυβικά Μέτρα
     */
    #[Label('Κυβικά Μέτρα')]
    case CUBIC_METERS = 6;

    /**
     * Τεμάχια_Λοιπές Περιπτώσεις
     */
    #[Label('Τεμάχια_Λοιπές Περιπτώσεις')]
    case PIECES_OTHER_CASES = 7;
}

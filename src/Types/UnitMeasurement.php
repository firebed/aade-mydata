<?php

namespace Firebed\AadeMyData\Types;

use Firebed\AadeMyData\Enums\HasLabels;

enum UnitMeasurement: int
{
    use HasLabels;

    /**
     * Τεμάχια
     */
    case PIECE = 1;

    /**
     * Κιλά
     */
    case KILOGRAM = 2;

    /**
     * Λίτρα
     */
    case LITRE = 3;

    /**
     * Μέτρα
     */
    case METER = 4;

    /**
     * Τετραγωνικά Μέτρα
     */
    case SQUARE_METER = 5;

    /**
     * Κυβικά Μέτρα
     */
    case CUBIC_METER = 6;

    /**
     * Τεμάχια_Λοιπές Περιπτώσεις
     */
    case OTHER = 7;

    public function label(): string
    {
        return match ($this) {
            self::PIECE => "Τεμάχια",
            self::KILOGRAM => "Κιλά",
            self::LITRE => "Λίτρα",
            self::METER => "Μέτρα",
            self::SQUARE_METER => "Τετραγωνικά Μέτρα",
            self::CUBIC_METER => "Κυβικά Μέτρα",
            self::OTHER => "Τεμάχια_Λοιπές Περιπτώσεις",
        };
    }
}
<?php

namespace Firebed\AadeMyData\Enums;

/**
 * @deprecated
 * @see \Firebed\AadeMyData\Types\UnitMeasurement
 */
enum UnitMeasurement: int
{
    use HasLabels;

    /**
     *  Τεμάχια
     * @deprecated
     * @see \Firebed\AadeMyData\Types\UnitMeasurement::PIECE
     */
    case UNIT_1 = 1;


    /**
     *  Κιλά
     * @deprecated
     * @see \Firebed\AadeMyData\Types\UnitMeasurement::KILOGRAM
     */
    case UNIT_2 = 2;


    /**
     *  Λίτρα
     * @deprecated
     * @see \Firebed\AadeMyData\Types\UnitMeasurement::LITRE
     */
    case UNIT_3 = 3;

    /**
     * Μέτρα
     * @deprecated
     * @see \Firebed\AadeMyData\Types\UnitMeasurement::METER
     */
    case UNIT_4 = 4;


    /**
     * Τετραγωνικά Μέτρα
     * @deprecated
     * @see \Firebed\AadeMyData\Types\UnitMeasurement::SQUARE_METER
     */
    case UNIT_5 = 5;


    /**
     * Κυβικά Μέτρα
     * @deprecated
     * @see \Firebed\AadeMyData\Types\UnitMeasurement::CUBIC_METER
     */
    case UNIT_6 = 6;


    /**
     * Τεμάχια_Λοιπές Περιπτώσεις
     * @deprecated
     * @see \Firebed\AadeMyData\Types\UnitMeasurement::OTHER
     */
    case UNIT_7 = 7;

    public function label(): string
    {
        return match ($this) {
            self::UNIT_1 => "Τεμάχια",
            self::UNIT_2 => "Κιλά",
            self::UNIT_3 => "Λίτρα",
            self::UNIT_4 => "Μέτρα",
            self::UNIT_5 => "Τετραγωνικά Μέτρα",
            self::UNIT_6 => "Κυβικά Μέτρα",
            self::UNIT_7 => "Τεμάχια_Λοιπές Περιπτώσεις",
        };
    }
}
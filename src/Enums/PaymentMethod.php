<?php

namespace Firebed\AadeMyData\Enums;

/**
 * @deprecated This enum will be removed in the next major version.
 * @see PaymentType
 */
enum PaymentMethod: int
{
    use HasLabels;

    /**
     *  Επαγ. Λογαριασμός Πληρωμών Ημεδαπής
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::WIRE_TRANSFER_DOMESTIC
     */
    case METHOD_1 = 1;


    /**
     *  Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::WIRE_TRANSFER_FOREIGN
     */
    case METHOD_2 = 2;


    /**
     *  Μετρητά
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::CASH
     */
    case METHOD_3 = 3;


    /**
     *  Επιταγή
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::CHECK
     */
    case METHOD_4 = 4;


    /**
     *  Επί Πιστώσει
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::CREDIT
     */
    case METHOD_5 = 5;

    /**
     * Web Banking
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::WEB_BANKING
     */
    case METHOD_6 = 6;

    /**
     * POS / e-POS
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::POS
     */
    case METHOD_7 = 7;


    /**
     * Άμεσες Πληρωμές IRIS
     * @deprecated This enum will be removed in the next major version.
     * @see PaymentType::IRIS_PAYMENTS
     */
    case METHOD_8 = 8;

    public function label(): string
    {
        return match ($this) {
            self::METHOD_1 => "Επαγ. Λογαριασμός Πληρωμών Ημεδαπής",
            self::METHOD_2 => "Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής",
            self::METHOD_3 => "Μετρητά",
            self::METHOD_4 => "Επιταγή",
            self::METHOD_5 => "Επί Πιστώσει",
            self::METHOD_6 => "Web Banking",
            self::METHOD_7 => "POS / e-POS",
            self::METHOD_8 => "Άμεσες Πληρωμές IRIS",
        };
    }
}
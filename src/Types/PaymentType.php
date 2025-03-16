<?php

namespace Firebed\AadeMyData\Types;

enum PaymentType: int
{
    use HasLabels;

    /**
     * Επαγγελματικός Λογαριασμός Πληρωμών Ημεδαπής
     */
    case WIRE_TRANSFER_DOMESTIC = 1;

    /**
     * Επαγγελματικός Λογαριασμός Πληρωμών Αλλοδαπής
     */
    case WIRE_TRANSFER_FOREIGN = 2;

    /**
     * Μετρητά
     */
    case CASH = 3;

    /**
     * Επιταγή
     */
    case CHECK = 4;

    /**
     * Επί Πιστώσει
     */
    case CREDIT = 5;

    /**
     * Web Banking
     */
    case WEB_BANKING = 6;

    /**
     * POS / e-POS
     */
    case POS = 7;

    /**
     * Άμεσες Πληρωμές IRIS
     */
    case IRIS_PAYMENTS = 8;

    public function label(): string
    {
        return match ($this) {
            self::WIRE_TRANSFER_DOMESTIC => "Επαγ. Λογαριασμός Πληρωμών Ημεδαπής",
            self::WIRE_TRANSFER_FOREIGN => "Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής",
            self::CASH => "Μετρητά",
            self::CHECK => "Επιταγή",
            self::CREDIT => "Επί Πιστώσει",
            self::WEB_BANKING => "Web Banking",
            self::POS => "POS / e-POS",
            self::IRIS_PAYMENTS => "Άμεσες Πληρωμές IRIS",
        };
    }
}

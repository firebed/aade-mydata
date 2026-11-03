<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum PaymentType: int
{
    use HasLabels;

    /**
     *  Επαγ. Λογαριασμός Πληρωμών Ημεδαπής
     */
    #[Label('Επαγ. Λογαριασμός Πληρωμών Ημεδαπής')]
    case DOMESTIC_PAYMENTS_ACCOUNT = 1;

    /**
     *  Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής
     */
    #[Label('Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής')]
    case FOREIGN_PAYMENTS_ACCOUNT = 2;

    /**
     *  Μετρητά
     */
    #[Label('Μετρητά')]
    case CASH = 3;

    /**
     *  Επιταγή
     */
    #[Label('Επιταγή')]
    case CHEQUE = 4;

    /**
     *  Επί Πιστώσει
     */
    #[Label('Επί Πιστώσει')]
    case ON_CREDIT = 5;

    /**
     * Web Banking
     */
    #[Label('Web Banking')]
    case WEB_BANKING = 6;

    /**
     * POS / e-POS
     */
    #[Label('POS / e-POS')]
    case POS = 7;

    /**
     * Άμεσες Πληρωμές IRIS
     */
    #[Label('Άμεσες Πληρωμές IRIS')]
    case IRIS = 8;
}

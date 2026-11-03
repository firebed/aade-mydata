<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum TransactionMode: int
{
    use HasLabels;

    /**
     * Αποδοχή του παραστατικού
     */
    #[Label('Αποδοχή του παραστατικού')]
    case ACCEPT = 0;

    /**
     * Απόρριψη του παραστατικού λόγω διαφωνίας
     */
    #[Label('Απόρριψη του παραστατικού λόγω διαφωνίας')]
    case REJECT = 1;

    /**
     * Απόρριψη του παραστατικού λόγω απόκλισης στα ποσά
     */
    #[Label('Απόρριψη του παραστατικού λόγω απόκλισης στα ποσά')]
    case DEVIATION = 2;
}

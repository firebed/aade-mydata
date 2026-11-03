<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum InvoiceDetailType: int
{
    use HasLabels;

    /**
     *  Εκκαθάριση Πωλήσεων Τρίτων
     */
    #[Label('Εκκαθάριση Πωλήσεων Τρίτων')]
    case THIRD_PARTY_SALES_SETTLEMENT = 1;

    /**
     *  Αμοιβή από Πωλήσεις Τρίτων
     */
    #[Label('Αμοιβή από Πωλήσεις Τρίτων')]
    case THIRD_PARTY_SALES_FEE = 2;
}

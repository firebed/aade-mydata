<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum StampCategory: int
{
    use HasLabels;

    /**
     *  Συντελεστής 1,2 % [1,20%]
     */
    #[Label('Συντελεστής 1.2 %')]
    case RATE_1_2 = 1;

    /**
     *  Συντελεστής 2,4 % [2,40%]
     */
    #[Label('Συντελεστής 2.4 %')]
    case RATE_2_4 = 2;

    /**
     *  Συντελεστής 3,6 % [3,60%]
     */
    #[Label('Συντελεστής 3.6 %')]
    case RATE_3_6 = 3;

    /**
     *  Λοιπές περιπτώσεις Ψηφιακού Τέλους Συναλλαγής [ποσό]
     */
    #[Label('Λοιπές περιπτώσεις Ψηφιακού Τέλους Συναλλαγής')]
    case OTHER_CASES = 4;
}

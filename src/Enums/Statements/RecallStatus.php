<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\Statements;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;

/**
 * Τύπος Ανάκλησης
 *
 * @version 1.0.12
 */
enum RecallStatus: int
{
    use HasLabels;

    /**
     * Μερική Ανάκληση
     */
    #[Label('Μερική Ανάκληση')]
    case PARTIAL_RECALL = 1;

    /**
     * Πλήρης Ανάκληση (Ακύρωση της Δήλωσης)
     */
    #[Label('Πλήρης Ανάκληση (Ακύρωση της Δήλωσης)')]
    case FULL_RECALL = 2;
}

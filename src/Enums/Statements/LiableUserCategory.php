<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\Statements;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;

/**
 * Κατηγορία Υπόχρεης Οντότητας
 *
 * @version 1.0.12
 */
enum LiableUserCategory: int
{
    use HasLabels;

    /**
     * Πάροχος
     */
    #[Label('Πάροχος')]
    case PROVIDER = 1;

    /**
     * ΙδιοΠάροχος
     */
    #[Label('ΙδιοΠάροχος')]
    case SELF_PROVIDER = 2;

    /**
     * Υπόχρεη Οντότητα
     */
    #[Label('Υπόχρεη Οντότητα')]
    case ENTITY = 3;
}

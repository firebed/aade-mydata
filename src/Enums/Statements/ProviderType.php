<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\Statements;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;

/**
 * Τύπος Παρόχου
 *
 * @version 1.0.12
 */
enum ProviderType: int
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
}

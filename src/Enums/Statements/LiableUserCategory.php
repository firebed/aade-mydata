<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\Statements;

use Firebed\AadeMyData\Enums\HasLabels;

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
    case PROVIDER = 1;

    /**
     * ΙδιοΠάροχος
     */
    case SELF_PROVIDER = 2;

    /**
     * Υπόχρεη Οντότητα
     */
    case ENTITY = 3;

    public function label(): string
    {
        return match ($this) {
            self::PROVIDER => 'Πάροχος',
            self::SELF_PROVIDER => 'ΙδιοΠάροχος',
            self::ENTITY => 'Υπόχρεη Οντότητα',
        };
    }
}

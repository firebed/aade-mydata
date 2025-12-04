<?php

namespace Firebed\AadeMyData\Enums;

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
    case PROVIDER = 1;

    /**
     * ΙδιοΠάροχος
     */
    case SELF_PROVIDER = 2;

    public function label(): string
    {
        return match ($this) {
            self::PROVIDER => 'Πάροχος',
            self::SELF_PROVIDER => 'ΙδιοΠάροχος',
        };
    }
}

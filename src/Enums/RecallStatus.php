<?php

namespace Firebed\AadeMyData\Enums;

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
    case PARTIAL_RECALL = 1;

    /**
     * Πλήρης Ανάκληση (Ακύρωση της Δήλωσης)
     */
    case FULL_RECALL = 2;

    public function label(): string
    {
        return match ($this) {
            self::PARTIAL_RECALL => 'Μερική Ανάκληση',
            self::FULL_RECALL => 'Πλήρης Ανάκληση (Ακύρωση της Δήλωσης)',
        };
    }
}

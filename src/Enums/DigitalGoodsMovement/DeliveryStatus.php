<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;
use Firebed\AadeMyData\Enums\Label;
use Firebed\AadeMyData\Enums\TryFromName;

enum DeliveryStatus: int implements TryFromName
{
    use HasLabels;

    #[Label('Εκδόθηκε')]
    case REGISTERED = 1;
    #[Label('Ακυρώθηκε')]
    case CANCELLED = 2;
    #[Label('Σε διακίνηση')]
    case IN_TRANSIT = 3;
    #[Label('Απορρίφθηκε')]
    case REJECTED = 4;
    #[Label('Παραδόθηκε από τον μεταφορέα')]
    case DELIVERED_BY_CARRIER = 5;
    #[Label('Αποτυχία παράδοσης')]
    case FAILED_DELIVERY = 7;
    #[Label('Ολοκληρώθηκε')]
    case COMPLETED = 8;
    #[Label('Σε διακίνηση (Επιστροφή)')]
    case IN_TRANSIT_RETURN = 9;

    /**
     * myDATA dev returns the names as REGISTERED, IN_TRANSIT_RETURN, … while the
     * specification spells them Registered, InTransit (Return), …, so case, spaces,
     * underscores and brackets are ignored.
     */
    public static function tryFromName(string $name): ?static
    {
        $name = strtoupper(preg_replace('/[^a-z0-9]/i', '', $name));

        foreach (self::cases() as $case) {
            if (str_replace('_', '', $case->name) === $name) {
                return $case;
            }
        }

        return null;
    }
}

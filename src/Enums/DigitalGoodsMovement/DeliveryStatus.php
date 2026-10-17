<?php

namespace Firebed\AadeMyData\Enums\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\HasLabels;

enum DeliveryStatus: int
{
    use HasLabels;

    case REGISTERED = 1;
    case CANCELLED = 2;
    case IN_TRANSIT = 3;
    case REJECTED = 4;
    case DELIVERED_BY_CARRIER = 5;
    case FAILED_DELIVERY = 7;
    case COMPLETED = 8;
    case IN_TRANSIT_RETURN = 9;

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => 'Εκδόθηκε',
            self::IN_TRANSIT => 'Σε διακίνηση',
            self::DELIVERED_BY_CARRIER => 'Παραδόθηκε από τον μεταφορέα',
            self::COMPLETED => 'Ολοκληρώθηκε',
            self::REJECTED => 'Απορρίφθηκε',
            self::CANCELLED => 'Ακυρώθηκε',
            self::FAILED_DELIVERY => 'Αποτυχία παράδοσης',
            self::IN_TRANSIT_RETURN => 'Σε διακίνηση (Επιστροφή)',
        };
    }

    /**
     * myDATA dev returns the names as REGISTERED, IN_TRANSIT_RETURN, … while the
     * specification spells them Registered, InTransit (Return), …, so case, spaces,
     * underscores and brackets are ignored.
     */
    public static function tryFromName(string $name): ?self
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

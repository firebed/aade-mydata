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
        };
    }

    public static function tryFromName(string $name): ?self
    {
        return match ($name) {
            'REGISTERED' => self::REGISTERED,
            'IN_TRANSIT' => self::IN_TRANSIT,
            'DELIVERED_BY_CARRIER' => self::DELIVERED_BY_CARRIER,
            'COMPLETED' => self::COMPLETED,
            'REJECTED' => self::REJECTED,
            'CANCELLED' => self::CANCELLED,
            'FAILED_DELIVERY' => self::FAILED_DELIVERY,
            default => null,
        };
    }
}

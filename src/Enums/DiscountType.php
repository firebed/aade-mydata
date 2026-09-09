<?php

namespace Firebed\AadeMyData\Enums;

/**
 * Not part of myDATA: how the value given to InvoiceDetails::setDiscount() is to be read by a gateway
 * that transmits through an e-invoicing provider.
 */
enum DiscountType: int
{
    use HasLabels;

    /**
     * Το ποσό έκπτωσης είναι ποσοστό επί της αξίας της γραμμής.
     */
    case PERCENTAGE = 1;

    /**
     * Το ποσό έκπτωσης είναι απόλυτο ποσό.
     */
    case AMOUNT = 2;

    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE => "Ποσοστό",
            self::AMOUNT => "Ποσό",
        };
    }
}

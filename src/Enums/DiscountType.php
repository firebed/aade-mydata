<?php

declare(strict_types=1);

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
    #[Label('Ποσοστό')]
    case PERCENTAGE = 1;

    /**
     * Το ποσό έκπτωσης είναι απόλυτο ποσό.
     */
    #[Label('Ποσό')]
    case AMOUNT = 2;
}

<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethods;
use Firebed\AadeMyData\Models\TaxesTotals;
use Firebed\AadeMyData\Models\TransportDetail;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'issuer'                => Issuer::factory(),
            'counterpart'           => Counterpart::factory(),
            'invoiceHeader'         => InvoiceHeader::factory(),
            'paymentMethods'        => PaymentMethods::factory(),
            'invoiceDetails'        => InvoiceDetails::factory(),
            'taxesTotals'           => TaxesTotals::factory(),
            'invoiceSummary'        => InvoiceSummary::factory(),
            'qrCodeUrl'             => fake()->url(),
            'otherTransportDetails' => TransportDetail::factory(),
            'downloadingInvoiceUrl' => fake()->url(),
        ];
    }
}
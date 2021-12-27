<?php

use Firebed\AadeMyData\Models\InvoiceType;
use Firebed\AadeMyData\Models\AddressType;
use Firebed\AadeMyData\Models\CancelledInvoicesDoc;
use Firebed\AadeMyData\Models\CancelledInvoiceType;
use Firebed\AadeMyData\Models\ContinuationTokenType;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Errors;
use Firebed\AadeMyData\Models\ErrorType;
use Firebed\AadeMyData\Models\ExpensesClassificationType;
use Firebed\AadeMyData\Models\IncomeClassificationType;
use Firebed\AadeMyData\Models\InvoiceExpensesClassificationType;
use Firebed\AadeMyData\Models\InvoiceHeaderType;
use Firebed\AadeMyData\Models\InvoiceIncomeClassificationType;
use Firebed\AadeMyData\Models\InvoiceRowType;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\InvoiceSummaryType;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetailType;
use Firebed\AadeMyData\Models\PaymentMethods;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Models\ResponseType;
use Firebed\AadeMyData\Models\ShipType;
use Firebed\AadeMyData\Models\TaxesTotals;
use Firebed\AadeMyData\Models\TaxTotalsType;

return [
    'InvoicesDoc'                => InvoicesDoc::class,
    'invoicesDoc'                => InvoicesDoc::class,
    'invoice'                    => InvoiceType::class,
    'issuer'                     => Issuer::class,
    'counterpart'                => Counterpart::class,
    'address'                    => AddressType::class,
    'invoiceHeader'              => InvoiceHeaderType::class,
    'paymentMethods'             => PaymentMethods::class,
    'paymentMethodDetails'       => PaymentMethodDetailType::class,
    'invoiceDetails'             => InvoiceRowType::class,
    'expensesClassification'     => ExpensesClassificationType::class,
    'incomeClassification'       => IncomeClassificationType::class,
    'invoiceSummary'             => InvoiceSummaryType::class,
    'taxesTotals'                => TaxesTotals::class,
    'taxes'                      => TaxTotalsType::class,
    'dienergia'                  => ShipType::class,
    'RequestedDoc'               => RequestedDoc::class,
    'continuationToken'          => ContinuationTokenType::class,
    'ResponseDoc'                => ResponseDoc::class,
    'response'                   => ResponseType::class,
    'incomeClassificationsDoc'   => InvoiceIncomeClassificationType::class,
    'expensesClassificationsDoc' => InvoiceExpensesClassificationType::class,
    'cancelledInvoicesDoc'       => CancelledInvoicesDoc::class,
    'cancelledInvoice'           => CancelledInvoiceType::class,
    'errors'                     => Errors::class,
    'error'                      => ErrorType::class,
];
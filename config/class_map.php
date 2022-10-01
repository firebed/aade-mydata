<?php

use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\BookInfo;
use Firebed\AadeMyData\Models\CancelledInvoice;
use Firebed\AadeMyData\Models\CancelledInvoicesDoc;
use Firebed\AadeMyData\Models\ContinuationToken;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Error;
use Firebed\AadeMyData\Models\Errors;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\PaymentMethods;
use Firebed\AadeMyData\Models\RequestedBookInfo;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\Response;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Models\Ship;
use Firebed\AadeMyData\Models\TaxesTotals;
use Firebed\AadeMyData\Models\TaxTotals;

return [
    'InvoicesDoc'                => InvoicesDoc::class,
    'RequestedDoc'               => RequestedDoc::class,
    'ResponseDoc'                => ResponseDoc::class,
    'RequestedBookInfo'          => RequestedBookInfo::class,
    'bookInfo'                   => BookInfo::class,
    'address'                    => Address::class,
    'cancelledInvoice'           => CancelledInvoice::class,
    'cancelledInvoicesDoc'       => CancelledInvoicesDoc::class,
    'continuationToken'          => ContinuationToken::class,
    'counterpart'                => Counterpart::class,
    'dienergia'                  => Ship::class,
    'error'                      => Error::class,
    'errors'                     => Errors::class,
    'expensesClassification'     => ExpensesClassification::class,
    'expensesClassificationsDoc' => InvoiceExpensesClassification::class,
    'incomeClassification'       => IncomeClassification::class,
    'incomeClassificationsDoc'   => InvoiceIncomeClassification::class,
    'invoice'                    => Invoice::class,
    'invoiceDetails'             => InvoiceDetails::class,
    'invoiceHeader'              => InvoiceHeader::class,
    'invoiceSummary'             => InvoiceSummary::class,
    'invoicesDoc'                => InvoicesDoc::class,
    'issuer'                     => Issuer::class,
    'paymentMethodDetails'       => PaymentMethodDetail::class,
    'paymentMethods'             => PaymentMethods::class,
    'response'                   => Response::class,
    'taxes'                      => TaxTotals::class,
    'taxesTotals'                => TaxesTotals::class,
];
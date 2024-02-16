<?php

use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\BookInfo;
use Firebed\AadeMyData\Models\CancelledInvoice;
use Firebed\AadeMyData\Models\CancelledInvoicesDoc;
use Firebed\AadeMyData\Models\ContinuationToken;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\ECRToken;
use Firebed\AadeMyData\Models\EntityType;
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
use Firebed\AadeMyData\Models\InvoiceVatDetail;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;
use Firebed\AadeMyData\Models\Party;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\PaymentMethodsDoc;
use Firebed\AadeMyData\Models\RequestedBookInfo;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\RequestedVatInfo;
use Firebed\AadeMyData\Models\Response;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Models\Ship;
use Firebed\AadeMyData\Models\TaxesTotals;
use Firebed\AadeMyData\Models\TaxTotals;
use Firebed\AadeMyData\Models\TransportDetailType;

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
    'response'                   => Response::class,
    'taxes'                      => TaxTotals::class,
    'taxesTotals'                => TaxesTotals::class,
    'otherTransportDetails'      => TransportDetailType::class, // v1.0.7
    'otherCorrelatedEntities'    => EntityType::class, // v1.0.7
    'entityData'                 => Party::class, // v1.0.7
    'otherDeliveryNoteHeader'    => OtherDeliveryNoteHeader::class, // v1.0.8
    'ECRToken'                   => ECRToken::class, // v1.0.8
    'paymentMethodsDoc'          => PaymentMethodsDoc::class, // v1.0.8
    'paymentMethods'             => PaymentMethod::class, // v1.0.8
    'loadingAddress'             => Address::class, // v1.0.8
    'deliveryAddress'            => Address::class, // v1.0.8
    'RequestedVatInfo'           => RequestedVatInfo::class, // v1.0.8
    'VatInfo'                    => InvoiceVatDetail::class, // v1.0.8
];
<?php

use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;
use Firebed\AadeMyData\Http\SendExpensesClassification;
use Firebed\AadeMyData\Http\SendIncomeClassification;
use Firebed\AadeMyData\Http\SendInvoices;

$dev = 'https://mydata-dev.azure-api.net/';
$prod = 'https://mydatapi.aade.gr/myDATA/';

return [
    'dev' => [
        CancelInvoice::class              => $dev . 'CancelInvoice',
        RequestDocs::class                => $dev . 'RequestDocs',
        RequestTransmittedDocs::class     => $dev . 'RequestTransmittedDocs',
        SendExpensesClassification::class => $dev . 'SendExpensesClassification',
        SendIncomeClassification::class   => $dev . 'SendIncomeClassification',
        SendInvoices::class               => $dev . 'SendInvoices',
    ],

    'prod' => [
        CancelInvoice::class              => $prod . 'CancelInvoice',
        RequestDocs::class                => $prod . 'RequestDocs',
        RequestTransmittedDocs::class     => $prod . 'RequestTransmittedDocs',
        SendExpensesClassification::class => $prod . 'SendExpensesClassification',
        SendIncomeClassification::class   => $prod . 'SendIncomeClassification',
        SendInvoices::class               => $prod . 'SendInvoices',
    ]
];
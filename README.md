# ΑΑΔΕ - AADE myDATA

[![Latest Version on Packagist](https://img.shields.io/packagist/v/firebed/aade-mydata.svg?style=flat-square)](https://packagist.org/packages/firebed/aade-mydata)
[![Total Downloads](https://poser.pugx.org/firebed/aade-mydata/downloads)](https://packagist.org/packages/firebed/aade-mydata)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/firebed/aade-mydata/php.yml)
[![PHP Version Require](https://poser.pugx.org/firebed/aade-mydata/require/php)](https://packagist.org/packages/firebed/aade-mydata)
[![License](https://poser.pugx.org/firebed/aade-mydata/license)](LICENSE.md)

## Support This Project

If you find this project useful, you can show your appreciation and support by giving it a ⭐. Your support motivates us to work harder and make even better and more useful tools!

## Introduction

This package provides an expressive, fluent interface to ΑΑΔΕ myDATA invoicing REST API. It handles almost all the boilerplate code for sending, cancelling and requesting invoices.

## Official Documentation

All documentation is available 👉 [on our documentation site](https://docs.invoicemaker.gr/getting-started)

## Upgrade Guide

If you are upgrading from a previous version, please see [upgrade guide](docs/upgrade-guide.md).

## v5 Features

### Ability to "squash" invoice rows

> Ο Πάροχος ηλεκτρονικής τιμολόγησης και τα ERP διαβιβάζουν υποχρεωτικά μόνο τη σύνοψη
γραμμών και χαρακτηρισμών των παραστατικών και όχι αναλυτικά τις γραμμές.
 
`$invoice->squashInvoiceRows()`

[See Invoice Row Squashing](/docs/squashing-invoice-rows.md) for more details.

### Ability to validate invoices

`$invoice->validate()`

### Ability to preview invoice xml

`$invoice->toXml()`

### Classification combinations

[See Classification Combinations](/docs/classifications.md) for more details.

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1));
```

### Classification combinations with labels

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabel());
dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabels())
dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1)->toKeyLabel())
```

### Classification combination validation

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Services\Classifications;

Classifications::incomeClassificationExists('1.1', 'category1_1', 'E3_561_001');
// or
Classifications::incomeClassificationExists(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1, IncomeClassificationType::E3_561_001);
// Outputs: true

// Same for expense classifications
Classifications::expenseClassificationExists('1.1', 'category2_1', 'E3_102_001');
````

### Added labels to all enum types

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentMethod;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\CountryCode;

echo InvoiceType::TYPE_1_1->label();
// Outputs: Τιμολόγιο Πώλησης

echo InvoiceType::TYPE_1_2->label();
// Outputs: Τιμολόγιο Πώλησης / Ενδοκοινοτικές Παραδόσεις

echo PaymentMethod::METHOD_5->label();
// Outputs: Επί Πιστώσει

echo VatCategory::VAT_1->label();
// Outputs: ΦΠΑ συντελεστής 24%

echo CountryCode::BE->label();
// Outputs: Βέλγιο
````

### Enum helper methods

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\CountryCode;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;

$invoiceType = InvoiceType::TYPE_1_1;
$invoiceType->supportsFuelInvoice();
$invoiceType->hasCounterpart();
$invoiceType->supportsDeliveryNote();
$invoiceType->supportsSelfPricing();
$invoiceType->supportsTaxFree();

var_dump(CountryCode::europeanUnionCountries());
// Outputs: All countries in the European Union

echo CountryCode::BE->isInEuropeanUnion()
// Outputs: true

echo CountryCode::US->isInEuropeanUnion()
// Outputs: false

$type = ExpenseClassificationType::VAT_361;
echo $type->isVatClassification(); // true

var_dump(ExpenseClassificationType::vatClassifications()); // Array of all vat classifications
```

### New enum types

- CountryCode
- CurrencyCode

### Ability to populate model attributes within constructor

```php
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;

new InvoiceDetails([
  'lineNumber' => 1,
  'netValue' => 5,
  'recType' => RecType::TYPE_2,
  'incomeClassification' => [
      [
          'classificationType' => IncomeClassificationType::E3_561_001,
          'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
          'amount' => '5'
      ]
  ]
])
```

### Fluent model setters (chainable)

`$invoice->setIssuer(...)->setCounterpart(...)`

### New methods

- Invoice::setTaxesTotals
- Invoice::setOtherTransportDetails

### `add_` methods to top up an amount to 

```php
$row->addNetValue(5);
$row->addVatAmount(1.2);
```

### Implemented endpoints for electronic invoice providers (Πάροχοι Ηλεκτρονικής Τιμολόγησης).

## Requirements

| Version | PHP | myDATA | Support |
|---------|-----|--------|---------|
| ^v5.x   | 8.1 | v1.0.9 | Active  |
| ^v4.x   | 8.1 | v1.0.8 | Ended   |
| ^v3.x   | 8.1 | v1.0.7 | Ended   |
| ^v2.x   | 8.1 | v1.0.5 | Ended   |
| ^v1.x   | 8.0 | v1.0.3 | Ended   |

- guzzlehttp/guzzle >= 7.0

## Installation

To install through Composer, run the following command:

```
composer require firebed/aade-mydata
```

## Documentation

Official myDATA webpage: [AADE myDATA](https://www.aade.gr/mydata)

Official myDATA documentation: [AADE myDATA REST API v1.0.8](https://www.aade.gr/sites/default/files/2024-07/myDATA%20API%20Documentation%20v1.0.9_official_erp.pdf)

In order to use this package, you will need first a **user id** and a **subscription key**. You can get these credentials by signing up to mydata rest api.

Development: [Sign up to mydata development api](https://mydata-dev-register.azurewebsites.net/)

Production: [Sign up to mydata production api](https://www.aade.gr/mydata)

### Setup

Once you have the user id and the subscription key use the following code to set the environment and the credentials:

```php
$env = "dev"; // For production use "prod"
$user_id = "your-user-id";
$subscription_key = "your-subscription-key";

MyDataRequest::setEnvironment($env);
MyDataRequest::setCredentials($user_id, $subscription_key);
```

For development, you may need to disable client verification if you are not using https:

```php
MyDataRequest::verifyClient(false);
```

### Available methods

| Method                                                                                      | Availability       |
|---------------------------------------------------------------------------------------------|--------------------|
| [Validate VAT Number](http://docs.invoicemaker.gr/http/search-vat)                          | :white_check_mark: |
| [SendInvoices](http://docs.invoicemaker.gr/http/send-invoices)                              | :white_check_mark: |
| [CancelInvoice](http://docs.invoicemaker.gr/http/cancel-invoice)                            | :white_check_mark: |
| [RequestDocs](http://docs.invoicemaker.gr/http/request-docs)                                | :white_check_mark: |
| [RequestTransmittedDocs](http://docs.invoicemaker.gr/http/request-transmitted-docs)         | :white_check_mark: |
| [RequestMyIncome](http://docs.invoicemaker.gr/http/request-my-income)                       | :white_check_mark: |
| [RequestMyExpenses](http://docs.invoicemaker.gr/http/request-my-expenses)                   | :white_check_mark: |
| [RequestVatInfo](http://docs.invoicemaker.gr/http/request-vat-info)                         | :white_check_mark: |
| [SendPaymentsMethod](http://docs.invoicemaker.gr/http/send-payments-method)                 | :white_check_mark: |
| [SendIncomeClassification](http://docs.invoicemaker.gr/http/send-income-classification)     | Soon               |
| [SendExpensesClassification](http://docs.invoicemaker.gr/http/send-expenses-classification) | Soon               |

### Testing

```shell
composer test
```

### Contributing

Please see [CONTRIBUTING](http://docs.invoicemaker.gr/contributing) for details.

### Licence

AADE myDATA is licenced under the [MIT License](LICENSE.md).

Copyright 2022 &copy; Okan Giritli

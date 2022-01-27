# ΑΑΔΕ myDATA

## Introduction
This package provides an expressive, fluent interface to ΑΑΔΕ myDATA invoicing REST API. It handles almost all the boilerplate code
for sending, cancelling and requesting invoices.

## Requirements
- PHP >= 8.0
- guzzlehttp/guzzle >= 7.0

## Installation
To install through Composer, run the following command:
```
composer require firebed/aade-mydata
```

## Auto-loading
By default, the package's classes are loaded automatically. There is nothing for you to do here.
In case the installation is done manually and not through the composer you will have to  require the necessary classes yourself (not recommended).

## Documentation
<p>Official documentation: <a href="https://www.aade.gr/sites/default/files/2021-09/myDATA%20API%20Documentation_ERP_v1.0.3_official.pdf">AADE myDATA REST API documentation.</a></p>
<p>In order to use this package, you will need first a <b>user id</b> and a <b>subscription key</b>. You can get these credentials by signing up to mydata rest api.</p>
<div>Development: <a href="https://mydata-register.azurewebsites.net/">Sign up to mydata development api</a></div>
<div>Production: <a href="https://www.aade.gr/mydata">Sign up to mydata production api</a></div>

### Setup
Once you have the user id and the subscription key use the following code to set the environment and the credentials:
```php
$env = "dev"; // For production use "prod"
$user_id = "your-user-id";
$subscription_key = "your-subscription-key";

MyDataRequest::setEnvironment($env);
MyDataRequest::setCredentials($user_id, $subscription_key);
```

### Sending invoices
<div>You can refer to the official or this package's documentation to see the details about the parameters.</div>
<div>Keep in mind that some parameters need to be in a specific order inside the xml request.</div>
<div>For example, if you set the counterpart before the issuer myDATA will throw an error. Yeah, I know!</div>
<div>Although, this behavior might have changed in the meantime, and it's time-consuming and pointless to track all these minor changes.</div>

```php
$invoiceType = new InvoiceType();
$invoiceType->setIssuer(...); // Firebed\AadeMyData\Models\Issuer
$invoiceType->setCounterpart(...); // Firebed\AadeMyData\Models\Counterpart
$invoiceType->setInvoiceHeader(...); // Firebed\AadeMyData\Models\InvoiceHeaderType
$invoiceType->addPaymentMethod(...); // Firebed\AadeMyData\Models\PaymentMethodDetailType
$invoiceType->addInvoiceRow(...); // Firebed\AadeMyData\Models\InvoiceRowType
$invoiceType->setInvoiceSummary(...); // Firebed\AadeMyData\Models\InvoiceSummaryType
            
$invoicesDoc = new InvoicesDoc();
$invoicesDoc->addInvoice($invoiceType);
// You can add multiple invoices at once

// Create the request
$sendInvoices = new SendInvoices();
$response = $sendInvoices->handle($invoicesDoc);

$errors = [];
foreach ($response->getResponseTypes() as $responseType) {
    if ($responseType->getStatusCode() === 'Success') {
        // This invoice was successfully registered. Typically, you should have an invoice object of your
        // own and an invoice reference from myDATA, and you will have to relate these together. 
        // Each responseType has an index value which corresponds to the index of the invoice in 
        // the $invoicesDoc object, you can use this index value to find the invoice it is referred to.
        // Afterward, get the invoice's uid and mark values from the responseType,
        // relate them with your local invoice and save them in your database.
        $index = $responseType->getIndex();
        $uid = $responseType->getInvoiceUid();
        $mark = $responseType->getInvoiceMark();
        $cancelledByMark = $responseType->getCancellationMark();
    } else {
        // There were some errors for this specific invoice. See errors for details.
        foreach ($responseType->getErrors()->getErrorsTypes() as $error) {
            $errors[$responseType->getIndex()][] = $error->getCode() . ': ' . $error->getMessage();
        }
    }
}
```

### Cancelling invoices
```php
$mark = "the-mark-of-invoice-to-cancel";
$cancelInvoice = new CancelInvoice();
$cancelInvoice->handle($mark)
```

### Requesting transmitted invoices
Transmitting invoices are the invoices that the entity hase issued itself.
myDATA returns a xml response with chunks of invoices (1000 each time I believe). You can use the
```$nextPartitionKey```  and ```$nextRowKey``` to paginate the results. Also, if you provide a non-empty
```$mark``` parameter myDATA will return the invoices that have mark value greater than the given parameter.

```php
$mark = "";
$nextPartitionKey = null;
$nextRowKey = null;

$request = new RequestTransmittedDocs();
$request->handle($mark, $nextPartitionKey, $nextRowKey);
```

### Requesting invoices
These invoices are issued by other companies and relate to this entity.

```php
$mark = "";
$nextPartitionKey = null;
$nextRowKey = null;

$request = new RequestDocs();
$request->handle($mark, $nextPartitionKey, $nextRowKey);
```

### Sending expenses and income classifications
<div>These features are not implemented yet.</div>
<p>If you send invoices using your ERP application myDATA will not allow you to send invoices without classifying them first either way.
When sending invoices from your ERP application using the SendInvoice api you will have to assign the classification for InvoiceSummaryType and for each InvoiceRowType.</p>

In my opinion, sending classifications separately refer to providers and accountants, but I am not sure, correct me otherwise.

### Contributing
Obviously, this package is not complete yet and all contributions are welcome. Your contribution will help
me and others that struggle through the mess the Greek government has created. Thank you!

### Licence
<p>AADE myDATA is licenced under the <a href="https://opensource.org/licenses/MIT">MIT License</a>.</p>

<p>Copyright 2022 Okan Giritli</p>
# ΑΑΔΕ - AADE myDATA


## Reason for forking

Changes for adding a Delivery note to an invoice.
Added isDeliveryNote and components that can be used with it.
Check out the SendInvoices sample code.

## Introduction

This package provides an expressive, fluent interface to ΑΑΔΕ myDATA invoicing REST API. It handles almost all the boilerplate code for sending, cancelling and requesting invoices.

## Requirements

| Version | PHP | myDATA |
|---------|-----|--------|
| ^v.4.x  | 8.1 | v1.0.8 |
| ^v.3.x  | 8.1 | v1.0.7 |
| ^v.2.x  | 8.1 | v1.0.5 |
| ^v.1.x  | 8.0 | v1.0.3 |

- guzzlehttp/guzzle >= 7.0

## Installation

To install through Composer, run the following command:

```
composer require firebed/aade-mydata
```

## Auto-loading

By default, the package's classes are loaded automatically. There is nothing for you to do here. In case the installation is done manually and not through the composer you will have to require the necessary classes yourself (not recommended).

## Documentation

<p>Official myDATA webpage: <a href="https://www.aade.gr/mydata">AADE myDATA</a></p>
<p>Official documentation: <a href="https://www.aade.gr/sites/default/files/2023-10/myDATA%20API%20Documentation%20v1.0.7_official_erp.pdf">AADE myDATA REST API v1.0.7.</a></p>
<p>In order to use this package, you will need first a <b>user id</b> and a <b>subscription key</b>. You can get these credentials by signing up to mydata rest api.</p>
<div>Development: <a href="https://mydata-dev-register.azurewebsites.net/">Sign up to mydata development api</a></div>
<div>Production: <a href="https://www.aade.gr/mydata">Sign up to mydata production api</a></div>

### Available methods

- [SendInvoices](#SendInvoices)
- [CancelInvoice](#CancelInvoice)
- [RequestTransmittedDocs](#RequestTransmittedDocs)
- [RequestDocs](#RequestDocs)
- [RequestMyIncome](#RequestMyIncome)
- [RequestMyExpenses](#RequestMyExpenses)

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

### SendInvoices

<p>You can refer to the official or this package's documentation to see the details about the parameters.</p>
<p>Keep in mind that some parameters need to be in a specific order inside the xml request.<br>
For example, if you set the counterpart before the issuer myDATA will throw an error.<br>
</p>

```php
$putDeliveryNote = true; //set true for put Delivery Note

//***************************************************//Στοιχεία Εκδότη
$issuer = new Issuer(); //Στοιχεία Εκδότη
$issuer->setVatNumber('000000000'); //Οποιοσδήποτε έγκυρος ΑΦΜ εκδότη
$issuer->setCountry('GR'); //Ο κωδικός της χώρας
$issuer->setBranch(0); //Αρ. Εγκατάσ. Σε περίπτωση που η εγκατάσταση του εκδότη είναι η έδρα ή δεν υφίσταται, το πεδίο να έχει την τιμή 0

if($putDeliveryNote){
$issuer->setName('Επωνυμία εκδότη');//Επωνυμία εκδότη, είναι αποδεκτό στην περίπτωση Ένδειξη Παραστατικού Διακίνησης isDeliveryNote=rtue
	$address = new Address(); //Διεύθυνση
	$address->setStreet('Οδός Οδός'); //Οδός
	$address->setNumber('40'); //Αριθμός Οδού
	$address->setPostalCode('11855'); //Ταχυδρομικός Κώδικας
	$address->setCity('Αθήνα'); //Όνομα πόλης
$issuer->setAddress($address); //Διεύθυνση εκδότη, είναι αποδεκτό στην περίπτωση Ένδειξη Παραστατικού Διακίνησης isDeliveryNote=rtue
}



//***************************************************//Στοιχεία Λήπτη
$counterpart = new Counterpart(); //Στοιχεία Λήπτη
$counterpart->setVatNumber('000000000'); //Οποιοσδήποτε έγκυρος ΑΦΜ εκδότη
$counterpart->setCountry('GR'); //Ο κωδικός της χώρας
$counterpart->setBranch(0);//Αρ. Εγκατάσ. Σε περίπτωση που η εγκατάσταση του εκδότη είναι η έδρα ή δεν υφίσταται, το πεδίο να έχει την τιμή 0
if($putDeliveryNote){
$counterpart->setName('Επωνυμία λήπτη');//Επωνυμία λήπτη, είναι αποδεκτό στην περίπτωση Ένδειξη Παραστατικού Διακίνησης isDeliveryNote=rtue
	$address = new Address(); //Διεύθυνση
	$address->setStreet('Οδός Οδός'); //Οδός
	$address->setNumber('45'); //Αριθμός Οδού
	$address->setPostalCode('11855'); //Ταχυδρομικός Κώδικας
	$address->setCity('Αθήνα'); //Όνομα πόλης
$counterpart->setAddress($address); //Διεύθυνση λήπτη, είναι αποδεκτό στην περίπτωση Ένδειξη Παραστατικού Διακίνησης isDeliveryNote=rtue
}



//***************************************************//Γενικά Στοιχεία παραστατικού
$invoiceHeader = new InvoiceHeader(); //Γενικά Στοιχεία παραστατικού
$invoiceHeader->setSeries('0'); //Σειρά παραστατικού, Σε περίπτωση μή έκδοσης σειράς παραστατικού, το πεδίο series πρέπει να έχει την τιμή 0
$invoiceHeader->setAa('1'); //ΑΑ Παραστατικού, μέγιστο επιτρεπτό μήκος 50
$invoiceHeader->setIssueDate('2024-02-03'); //Ημερομηνία Έκδοσης Παραστατικού
$invoiceHeader->setInvoiceType('1.1'); //Είδος Παραστατικού
$invoiceHeader->setCurrency('EUR'); //Νόμισμα, Ο κωδικός νομισμάτων προέρχεται από την αντίστοιχη λίστα σύμφωνα με το πρότυπο ISO4217.

if($putDeliveryNote){
$invoiceHeader->setMovePurpose('1'); //Σκοπός Διακίνησης
$otherDeliveryNoteHeader = new OtherDeliveryNoteHeader(); //Λοιπά Γενικά Στοιχεία Διακίνησης
	$loadaddress = new LoadingAddress(); //Διεύθυνση Φόρτωσης
	$loadaddress->setStreet('Οδός Οδός'); //Οδός
	$loadaddress->setNumber('40'); //Αριθμός Οδού
	$loadaddress->setPostalCode('11855'); //Ταχυδρομικός Κώδικας
	$loadaddress->setCity('Αθήνα'); //Όνομα πόλης
$otherDeliveryNoteHeader->setLoadingAddress($loadaddress); //Ορισμός, Διεύθυνση Φόρτωσης
	$delivryaddress = new DeliveryAddress(); //Διεύθυνση Παράδοσης
	$delivryaddress->setStreet('Οδός Οδός'); //Οδός
	$delivryaddress->setNumber('45'); //Αριθμός Οδού
	$delivryaddress->setPostalCode('11855'); //Ταχυδρομικός Κώδικας
	$delivryaddress->setCity('Αθήνα'); //Όνομα πόλης
$otherDeliveryNoteHeader->setDeliveryAddress($delivryaddress); //Ορισμός, Διεύθυνση Παράδοσης
$invoiceHeader->setOtherDeliveryNoteHeader($otherDeliveryNoteHeader); //Ορισμός, Λοιπά Γενικά Στοιχεία Διακίνησης
$invoiceHeader->setIsDeliveryNote(true); //Ένδειξη Παραστατικού Διακίνησης
}



//***************************************************//Στοιχεία Πληρωμών
$paymentMethod = new PaymentMethodDetail(); //Στοιχεία Πληρωμών
$paymentMethod->setType('3'); //Τύπος Πληρωμής
$paymentMethod->setAmount(124.00); //Αναλογούν Ποσό
$paymentMethod->setPaymentMethodInfo('Μετρητά'); //Λοιπές Πληροφορίες (Τρόπος Πληρωμής)




//***************************************************//Λεπτομέρειες Παραστατικού
//Add one new product
$invoiceDetails = new InvoiceDetails(); //Λεπτομέρειες Παραστατικού
$invoiceDetails->setLineNumber(1); //ΑΑ Γραμμής
if($putDeliveryNote){
$invoiceDetails->setItemCode('KE58'); //Κωδικός Είδους
$invoiceDetails->setItemDescr('Είδος KE58'); //Περιγραφή Είδους
$invoiceDetails->setQuantity(1); //Ποσότητα
$invoiceDetails->setMeasurementUnit('1'); //Είδος Ποσότητας
}
$invoiceDetails->setNetValue(100.00); //Καθαρή Αξία
$invoiceDetails->setVatCategory('1'); //Κατηγορία ΦΠΑ
$invoiceDetails->setVatAmount(24.00); //Ποσό ΦΠΑ

$incomeClassification = new IncomeClassification(); //Λίστα Χαρακτηρισμών Εσόδων
$incomeClassification->setClassificationType('E3_561_001'); //Κωδικός Χαρακτηρισμού
$incomeClassification->setClassificationCategory('category1_1'); //Κατηγορία Χαρακτηρισμού
$incomeClassification->setAmount(100.00); //Ποσό
$invoiceDetails->addIncomeClassification($incomeClassification); //Ορισμός, Λίστα Χαρακτηρισμών Εσόδων

//Continue to new product or multiple products
//ok let's add the second one too
$invoiceDetailsb = new InvoiceDetails(); //Λεπτομέρειες Παραστατικού
$invoiceDetailsb->setLineNumber(2); //ΑΑ Γραμμής
if($putDeliveryNote){
$invoiceDetailsb->setItemCode('BE51'); //Κωδικός Είδους
$invoiceDetailsb->setItemDescr('Είδος BE51'); //Περιγραφή Είδους
$invoiceDetailsb->setQuantity(1); //Ποσότητα
$invoiceDetailsb->setMeasurementUnit('1'); //Είδος Ποσότητας
}
$invoiceDetailsb->setNetValue(100.00); //Καθαρή Αξία
$invoiceDetailsb->setVatCategory('1'); //Κατηγορία ΦΠΑ
$invoiceDetailsb->setVatAmount(24.00); //Ποσό ΦΠΑ

$incomeClassification = new IncomeClassification(); //Λίστα Χαρακτηρισμών Εσόδων
$incomeClassification->setClassificationType('E3_561_001'); //Κωδικός Χαρακτηρισμού
$incomeClassification->setClassificationCategory('category1_2'); //Κατηγορία Χαρακτηρισμού
$incomeClassification->setAmount(100.00); //Ποσό
$invoiceDetailsb->addIncomeClassification($incomeClassification); //Ορισμός, Λίστα Χαρακτηρισμών Εσόδων




//***************************************************//Συγκεντρωτικά Στοιχεία
$invoiceSummary = new InvoiceSummary(); //Συγκεντρωτικά Στοιχεία
$invoiceSummary->setTotalNetValue(200.00); //Σύνολο Καθαρής Αξίας
$invoiceSummary->setTotalVatAmount(48.00); //Σύνολο ΦΠΑ
$invoiceSummary->setTotalWithheldAmount(0.00); //Σύνολο Παρακρατήσεων Φόρων
$invoiceSummary->setTotalFeesAmount(0.00); //Σύνολο Τελών
$invoiceSummary->setTotalStampDutyAmount(0.00); //Σύνολο Χαρτοσήμου
$invoiceSummary->setTotalOtherTaxesAmount(0.00); //Σύνολο Λοιπών Φόρων
$invoiceSummary->setTotalDeductionsAmount(0.00); //Σύνολο Κρατήσεων
$invoiceSummary->setTotalGrossValue(248.00); //Συνολική Αξία

//IncomeClassification for first product
$incomeClassification = new IncomeClassification(); //Λίστα Χαρακτηρισμών Εσόδων
$incomeClassification->setClassificationType('E3_561_001'); //Κωδικός Χαρακτηρισμού
$incomeClassification->setClassificationCategory('category1_1'); //Κατηγορία Χαρακτηρισμού
$incomeClassification->setAmount(100.00); //Ποσό
$invoiceSummary->addIncomeClassification($incomeClassification); //Ορισμός, Λίστα Χαρακτηρισμών Εσόδων

//IncomeClassification for second product
$incomeClassification = new IncomeClassification(); //Λίστα Χαρακτηρισμών Εσόδων
$incomeClassification->setClassificationType('E3_561_001'); //Κωδικός Χαρακτηρισμού
$incomeClassification->setClassificationCategory('category1_2'); //Κατηγορία Χαρακτηρισμού
$incomeClassification->setAmount(100.00); //Ποσό
$invoiceSummary->addIncomeClassification($incomeClassification); //Ορισμός, Λίστα Χαρακτηρισμών Εσόδων

//Continue IncomeClassification for multiple products 




$invoice = new Invoice();
$invoice->setIssuer($issuer); // Firebed\AadeMyData\Models\Issuer
$invoice->setCounterpart($counterpart); // Firebed\AadeMyData\Models\Counterpart
$invoice->setInvoiceHeader($invoiceHeader); // Firebed\AadeMyData\Models\InvoiceHeader
$invoice->addPaymentMethod($paymentMethod); // Firebed\AadeMyData\Models\PaymentMethodDetail
$invoice->addInvoiceDetails($invoiceDetails); // Firebed\AadeMyData\Models\InvoiceDetails
$invoice->addInvoiceDetails($invoiceDetailsb); // Firebed\AadeMyData\Models\InvoiceDetails
$invoice->setInvoiceSummary($invoiceSummary); // Firebed\AadeMyData\Models\InvoiceSummary
            
$invoicesDoc = new InvoicesDoc();
$invoicesDoc->addInvoice($invoice);
// You can add multiple invoices at once


/*
var_dump($invoicesDoc);
exit();
*/

// Create the request
$sendInvoices = new SendInvoices();
$response = $sendInvoices->handle($invoicesDoc);


function dd($testtt){
	var_dump($testtt);
}

$errors = [];
foreach ($response as $responseType) {
    if ($responseType->isSuccessful()) {
        // This invoice was successfully registered. Typically, you should have an invoice object of your
        // own and an invoice reference from myDATA, and you will have to relate these together. 
        // Each responseType has an index value which corresponds to the index of the invoice in 
        // the $invoicesDoc object, you can use this index value to find the invoice it is referred to.
        // Retrieve the invoice's uid, mark, qr and other values from the responseType,
        // then establish the correlation with your local invoice and persist these details in your database.
        $index = $responseType->getIndex();
        $uid = $responseType->getInvoiceUid();
        $mark = $responseType->getInvoiceMark();
        $cancelledByMark = $responseType->getCancellationMark();
        $qrUrl = $responseType->getQrUrl();

        dd(compact('index', 'uid', 'mark', 'cancelledByMark', 'qrUrl'));
    } else {
        // There were some errors for this specific invoice. See errors for details.
        foreach ($responseType->getErrors() as $error) {
            $errors[$responseType->getIndex()][] = $error->getCode() . ': ' . $error->getMessage();
        }
    }
}

if (!empty($errors)) {
    dd(["Errors: " => $errors]);
}
```

### CancelInvoice

```php
$mark = "the-mark-of-invoice-to-cancel";
$cancelInvoice = new CancelInvoice();
$cancelInvoice->handle($mark);
```

### RequestTransmittedDocs

Transmitted invoices are the invoices that the entity has issued itself. myDATA returns a xml response with chunks of invoices (1000 each time I believe). You can use the
```$nextPartitionKey```  and ```$nextRowKey``` to paginate the results. Also, if you provide a non-empty
```$mark``` parameter myDATA will return the invoices that have mark value greater than the given parameter.

```php
$mark = "";
$nextPartitionKey = null;
$nextRowKey = null;

$request = new RequestTransmittedDocs();
$request->handle($mark, $nextPartitionKey, $nextRowKey);
```

### RequestDocs

These invoices are issued by other companies and relate to this entity.

```php
$mark = "";
$nextPartitionKey = null;
$nextRowKey = null;

$request = new RequestDocs();
$request->handle($mark, $nextPartitionKey, $nextRowKey);
```

### RequestMyIncome

```php
$dateFrom = "01/01/2022";
$dateTo = "31/12/2022";

$request = new RequestMyIncome();
$request->handle($dateFrom, $dateTo);
```

### RequestMyExpenses

```php
$dateFrom = "01/01/2022";
$dateTo = "31/12/2022";

$request = new RequestMyExpenses();
$request->handle($dateFrom, $dateTo);
```

### Sending expenses and income classifications

<div>These features are not implemented yet.</div>
<p>If you send invoices using your ERP application myDATA will not allow you to send invoices without classifying them first either way.
When sending invoices from your ERP application using the SendInvoice api you will have to assign the classification for InvoiceSummaryType and for each InvoiceRowType.</p>

### Contributing

Obviously, this package is not complete yet and all contributions are welcome. Your contribution will help me and others that struggle through the mess the Greek government has created. Thank you!

### Licence

<p>AADE myDATA is licenced under the <a href="https://opensource.org/licenses/MIT">MIT License</a>.</p>

<p>Copyright 2022 Okan Giritli</p>

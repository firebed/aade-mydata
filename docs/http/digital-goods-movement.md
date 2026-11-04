# Digital Goods Movement

Track a delivery note from dispatch to delivery. The carrier, the recipient and the issuer each report their step
to myDATA. RequestDeliveryNoteStatus returns the note's status and history.

> [!WARNING]
> ERP channel only. On a provider connection every call on this page throws `UnsupportedChannelException`
> and sends nothing.

| Call                                                    | Called by            | Note status after                                  |
|---------------------------------------------------------|----------------------|----------------------------------------------------|
| [RegisterTransfer](#registertransfer)                   | Carrier              | `IN_TRANSIT`                                       |
| [ConfirmDeliveryOutcome](#confirmdeliveryoutcome)       | Carrier or recipient | `DELIVERED_BY_CARRIER`, `COMPLETED` or `FAILED_DELIVERY` |
| [ConfirmDeliveryReturn](#confirmdeliveryreturn)         | Issuer               | `COMPLETED`                                        |
| [RejectDeliveryNote](#rejectdeliverynote)               | Recipient            | `REJECTED`                                         |
| [RequestDeliveryNoteStatus](#requestdeliverynotestatus) | -                    | -                                                  |
| [GenerateGroupQrCode](#generategroupqrcode)             | Issuer or carrier    | -                                                  |
| [RequestGroupQrDetails](#requestgroupqrdetails)         | -                    | -                                                  |

Every call has a method on the `MyData` client too:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$mydata->registerTransfer($transport);
$mydata->confirmDeliveryOutcome($outcome);
$mydata->confirmDeliveryReturn($return);
$mydata->rejectDeliveryNote($rejection);
$mydata->requestDeliveryNoteStatus($mark);
$mydata->requestDeliveryNoteStatusByQrUrl($qrUrl);
$mydata->generateGroupQrCode($groupQrCode);
$mydata->requestGroupQrDetails($groupId);
```

## RegisterTransfer

```shell
# production
https://mydatapi.aade.gr/myDATA/RegisterTransfer

# development
https://mydataapidev.aade.gr/RegisterTransfer
```

The carrier declares that it picked up the goods and the movement started, or that it took them over from a
previous carrier (transshipment). The note moves to `IN_TRANSIT`. Returns a [ResponseDoc](#responses) with the
`transferMark`.

```php
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\TransportType;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RegisterTransfer;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Location;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\TransportDetails;

$details = (new TransportDetails)
    ->setVehicleNumber('AHN0011')
    ->setTransportType(TransportType::PRIVATE_USE_TRUCK)
    ->setCarrierVatNumber('777777777')
    ->setTrailorNumber('P22345')                      // optional
    ->setLocation(new Location(40.640063, 22.944419)); // optional: latitude, longitude

$transport = (new Transport)
    ->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...')
    ->setTransportDetail($details);

$response = (new RegisterTransfer)->handle($transport)->first();

if ($response->isSuccessful()) {
    echo $response->getTransferMark();
}
```

`qrUrl` can also be a [group QR URL](#generategroupqrcode), to update every note of the group at once.

### TransportDetails fields

| Field                 | Required | Description                                                              |
|-----------------------|----------|--------------------------------------------------------------------------|
| `vehicleNumber`       | Yes      | Vehicle plate, ship name, route or flight code. Max 50 characters        |
| `transportType`       | Yes      | [TransportType](#transporttype)                                          |
| `carrierVatNumber`    | Yes      | VAT number of the carrier. Max 20 characters                             |
| `pNumber`             | No       | Trailer plate ("P" number). Max 50 characters. Setter: `setTrailorNumber()` |
| `location`            | No       | Transshipment location. `new Location($latitude, $longitude)`            |
| `packingsDeclaration` | No       | Packages declared by the carrier, a list of `PackagingDetail` (v2.0.2). Add with `addPackingsDeclaration()` |

## ConfirmDeliveryOutcome

```shell
# production
https://mydatapi.aade.gr/myDATA/ConfirmDeliveryOutcome

# development
https://mydataapidev.aade.gr/ConfirmDeliveryOutcome
```

The carrier declares the delivery outcome, or the recipient confirms receipt. Returns a [ResponseDoc](#responses)
with the `deliveryOutcomeMark`.

| Called by                   | Note status after      |
|-----------------------------|------------------------|
| Carrier, B2B transaction    | `DELIVERED_BY_CARRIER` |
| Carrier, B2C transaction    | `COMPLETED`            |
| Recipient                   | `COMPLETED`            |
| Anyone, with outcome `NONE` | `FAILED_DELIVERY`      |

```php
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\ConfirmDeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;

$outcome = (new DeliveryOutcome)
    ->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...')
    ->setOutcome(DeliveryOutcomeType::FULL)
    ->setDeliveredWithoutRecipient(false)                            // optional
    ->addDeliveredPackaging(new PackagingDetail(PackagingType::CRATE, 10)) // optional
    ->addDeliveredPackaging(new PackagingDetail(PackagingType::BOX, 2));

$response = (new ConfirmDeliveryOutcome)->handle($outcome)->first();

if ($response->isSuccessful()) {
    echo $response->getDeliveryOutcomeMark();
}
```

### DeliveryOutcome fields

| Field                       | Required | Description                                             |
|-----------------------------|----------|---------------------------------------------------------|
| `qrUrl`                     | Yes      | QR URL of the note, or a group QR URL                   |
| `outcome`                   | Yes      | [DeliveryOutcomeType](#deliveryoutcometype)             |
| `deliveredWithoutRecipient` | No       | Delivered without the recipient being there             |
| `deliveredPackaging`        | No       | Packages delivered, a list of `PackagingDetail`         |

`PackagingDetail` takes a [PackagingType](#packagingtype) and a quantity. For `PackagingType::OTHER`, also call
`setOtherPackagingTypeTitle()` (max 150 characters).

## ConfirmDeliveryReturn

```shell
# production
https://mydatapi.aade.gr/myDATA/ConfirmDeliveryReturn

# development
https://mydataapidev.aade.gr/ConfirmDeliveryReturn
```

Added in v2.0.2. The issuer declares that the movement ended in a return: the carrier did not deliver all the goods
and brings them back. The note moves to `COMPLETED`. Returns a [ResponseDoc](#responses) with the
`deliveryReturnMark`.

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\ConfirmDeliveryReturn;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryReturn;

$return = new DeliveryReturn('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...');

$response = (new ConfirmDeliveryReturn)->handle($return)->first();

if ($response->isSuccessful()) {
    echo $response->getDeliveryReturnMark();
}
```

| Field   | Required | Description                             |
|---------|----------|-----------------------------------------|
| `qrUrl` | Yes      | QR URL of the note, or a group QR URL   |

## RejectDeliveryNote

```shell
# production
https://mydatapi.aade.gr/myDATA/RejectDeliveryNote

# development
https://mydataapidev.aade.gr/RejectDeliveryNote
```

The recipient rejects all the goods of the note. Only the recipient can call it. The note moves to `REJECTED`,
a final status. Returns a [ResponseDoc](#responses) with the `rejectMark`.

`DeliveryRejection` takes the QR URL or the MARK of the note, and an optional reason.

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RejectDeliveryNote;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;

// By MARK
$rejection = new DeliveryRejection(400001234567890, 'Damaged goods');

// Or by QR URL
$rejection = new DeliveryRejection('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...', 'Damaged goods');

$response = (new RejectDeliveryNote)->handle($rejection)->first();

if ($response->isSuccessful()) {
    echo $response->getRejectMark();
}
```

Shortcuts:

```php
$reject = new RejectDeliveryNote;

$reject->rejectUsingMark(400001234567890, 'Damaged goods');
$reject->rejectUsingQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...', 'Damaged goods');
```

| Field                    | Required | Description                                   |
|--------------------------|----------|-----------------------------------------------|
| `qrUrl` or `invoiceMark` | Yes      | QR URL or MARK of the note                    |
| `rejectionReason`        | No       | Reason for the rejection. Max 150 characters  |

## RequestDeliveryNoteStatus

```shell
# production
https://mydatapi.aade.gr/myDATA/GetDeliveryNoteStatus

# development
https://mydataapidev.aade.gr/GetDeliveryNoteStatus
```

Returns the current status of a delivery note and its full history of events. Search by MARK, or by QR URL (v2.0.2).
Returns a [DeliveryNoteStatusResponse](#deliverynotestatusresponse).

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestDeliveryNoteStatus;

$status = (new RequestDeliveryNoteStatus)->handle(400001234567890);

// Or by QR URL
$status = (new RequestDeliveryNoteStatus)->handleUsingQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...');

echo $status->getStatus()?->label();

foreach ($status->getLifecycleHistory() ?? [] as $event) {
    echo $event->getEventType()?->label().' '.$event->getEventTimestamp().' '.$event->getActorVat().PHP_EOL;

    $event->getMark();
    $event->getTransportDetails()?->getVehicleNumber(); // RegisterTransfer events
    $event->getOutcomeDetails()?->getOutcome();         // ConfirmOutcome events
    $event->getRejectionDetails()?->getReason();        // Rejection events
}
```

| Parameter         | Required | Description                                                          |
|-------------------|----------|----------------------------------------------------------------------|
| `mark`            | Yes*     | MARK of the note. `handle()`                                         |
| `issuerVatNumber` | No       | VAT number of the issuer. `handle()` only                            |
| `qrUrl`           | Yes*     | QR URL of the note. `handleUsingQrUrl()`                             |

\* Send `mark` or `qrUrl`. myDATA answers HTTP 400 when `issuerVatNumber` comes with `qrUrl`, so
`handleUsingQrUrl()` never sends it. Pass the QR URL as myDATA gave it: the library decodes it once so it is not
encoded twice.

## GenerateGroupQrCode

```shell
# production
https://mydatapi.aade.gr/myDATA/GenerateGroupQRCode

# development
https://mydataapidev.aade.gr/GenerateGroupQRCode
```

Combines the QR codes of two or more delivery notes into one group QR code. Use the group QR URL in
`RegisterTransfer`, `ConfirmDeliveryOutcome` and `RejectDeliveryNote` to update every note of the group at once.
The group QR URL expires: see `getExpiresAt()`. Returns a [GroupQrCodeResponse](#groupqrcoderesponse).

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\GenerateGroupQrCode;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCode;

$group = (new GroupQrCode)
    ->addQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...1')
    ->addQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=...2');

$response = (new GenerateGroupQrCode)->handle($group);

// Or from an array of QR URLs
$response = (new GenerateGroupQrCode)->generateFromQrUrls([$qrUrl1, $qrUrl2]);

$response->getGroupQrUrl();
$response->getGroupId();
$response->getExpiresAt();
```

| Field    | Required | Description                     |
|----------|----------|---------------------------------|
| `qrUrls` | Yes      | QR URLs of the notes, at least 2 |

## RequestGroupQrDetails

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestGroupQRDetails

# development
https://mydataapidev.aade.gr/RequestGroupQRDetails
```

Returns the QR URLs inside a group QR code, their count, the creator's VAT number and the expiry date.
Returns a [GroupQrDetailsResponse](#groupqrdetailsresponse).

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestGroupQrDetails;

$details = (new RequestGroupQrDetails)->handle('9946C6AA9DB3658C85CC3BF43DB726FE25BBC555');

foreach ($details->getQrUrls() ?? [] as $qrUrl) {
    echo $qrUrl.PHP_EOL;
}
```

| Parameter | Required | Description                                                    |
|-----------|----------|----------------------------------------------------------------|
| `groupId` | Yes      | ID of the group. `GroupQrCodeResponse::getGroupId()` gives it  |

## Responses

### ResponseDoc

`RegisterTransfer`, `ConfirmDeliveryOutcome`, `ConfirmDeliveryReturn` and `RejectDeliveryNote` return a
`Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc`, a list of
`Firebed\AadeMyData\Models\DigitalGoodsMovement\Response`.

| Method                     | Returns   | Description                                  |
|----------------------------|-----------|----------------------------------------------|
| `isSuccessful()`           | `bool`    | `statusCode` is `Success`                    |
| `isFailed()`               | `bool`    | Has errors                                   |
| `getStatusCode()`          | `?string` | Result code                                  |
| `getIndex()`               | `?int`    | Position of the entity in the request        |
| `getTransferMark()`        | `?int`    | RegisterTransfer                             |
| `getDeliveryOutcomeMark()` | `?int`    | ConfirmDeliveryOutcome                       |
| `getDeliveryReturnMark()`  | `?int`    | ConfirmDeliveryReturn (v2.0.2)               |
| `getRejectMark()`          | `?string` | RejectDeliveryNote                           |
| `getErrors()`              | `?Errors` | Errors, each with `getCode()` and `getMessage()` |

```php
foreach ($responseDoc as $response) {
    if ($response->isFailed()) {
        foreach ($response->getErrors() as $error) {
            echo $error->getCode().': '.$error->getMessage().PHP_EOL;
        }
    }
}
```

### DeliveryNoteStatusResponse

| Method                  | Returns                | Description                         |
|-------------------------|------------------------|-------------------------------------|
| `getInvoiceMark()`      | `?string`              | MARK of the note                    |
| `getStatus()`           | `?DeliveryStatus`      | [DeliveryStatus](#deliverystatus)   |
| `getDispatchTimestamp()`| `?string`              | Dispatch time                       |
| `getLifecycleHistory()` | `?DeliveryEvent[]`     | History of events                   |

`DeliveryEvent`: `getEventType()` ([DeliveryEventType](#deliveryeventtype)), `getEventTimestamp()`, `getActorVat()`,
`getMark()`, and one of `getTransportDetails()`, `getOutcomeDetails()`, `getRejectionDetails()`.

### GroupQrCodeResponse

| Method              | Returns   | Description                                |
|---------------------|-----------|--------------------------------------------|
| `getStatusCode()`   | `?string` | Result code                                |
| `getGroupQrUrl()`   | `?string` | The group QR URL                           |
| `getGroupId()`      | `?string` | The `groupId` read from the group QR URL   |
| `getQrUrlsCount()`  | `?int`    | Number of notes in the group               |
| `getExpiresAt()`    | `?string` | When the group QR URL expires              |

### GroupQrDetailsResponse

| Method                        | Returns   | Description                         |
|-------------------------------|-----------|-------------------------------------|
| `getGroupId()`                | `?string` | ID of the group                     |
| `getQrUrls()`                 | `?QrUrls` | The QR URLs, a list of strings      |
| `getQrUrlsCount()`            | `?int`    | Number of QR URLs                   |
| `getGroupQrCreatorVatNumber()`| `?string` | VAT number of the creator           |
| `getCreatedAt()`              | `?string` | Creation time                       |
| `getExpiresAt()`              | `?string` | Expiry time                         |
| `getStatusCode()`             | `?string` | Result code                         |
| `getMessage()`                | `?string` | Message                             |

## Code lists

Enums in `Firebed\AadeMyData\Enums\DigitalGoodsMovement`. `label()` returns the Greek label.

### TransportType

| Code | Case                    | Label                    | English                     |
|------|-------------------------|--------------------------|-----------------------------|
| 1    | `PUBLIC_USE_TRUCK`      | Φορτηγό Δημόσιας Χρήσης  | Public use truck            |
| 2    | `PRIVATE_USE_TRUCK`     | Φορτηγό Ιδιωτικής Χρήσης | Private use truck           |
| 3    | `SHIP`                  | Πλοίο                    | Ship                        |
| 4    | `TRAIN`                 | Τρένο                    | Train                       |
| 5    | `AIRPLANE`              | Αεροπλάνο                | Airplane                    |
| 6    | `OTHER_TRANSPORT_MEANS` | Λοιπά Μεταφορικά Μέσα    | Other means of transport    |
| 7    | `WITHOUT`               | Άνευ                     | None                        |

### DeliveryOutcomeType

| Code      | Case      | Label           | English          |
|-----------|-----------|-----------------|------------------|
| `FULL`    | `FULL`    | Πλήρης Παράδοση | Full delivery    |
| `PARTIAL` | `PARTIAL` | Μερική Παράδοση | Partial delivery |
| `NONE`    | `NONE`    | Καμία Παράδοση  | No delivery      |

### PackagingType

| Code | Case     | Label   | English                                   |
|------|----------|---------|-------------------------------------------|
| 1    | `PALLET` | Παλέτα  | Pallet                                    |
| 2    | `BOX`    | Κούτα   | Box                                       |
| 3    | `CRATE`  | Κιβώτιο | Crate                                     |
| 4    | `BARREL` | Βαρέλι  | Barrel                                    |
| 5    | `SACK`   | Σάκος   | Sack                                      |
| 6    | `OTHER`  | Λοιπά   | Other. Needs `otherPackagingTypeTitle`    |

### DeliveryStatus

| Code | Case                   | Label                        | English                    |
|------|------------------------|------------------------------|----------------------------|
| 1    | `REGISTERED`           | Εκδόθηκε                     | Issued                     |
| 2    | `CANCELLED`            | Ακυρώθηκε                    | Cancelled                  |
| 3    | `IN_TRANSIT`           | Σε διακίνηση                 | In transit                 |
| 4    | `REJECTED`             | Απορρίφθηκε                  | Rejected                   |
| 5    | `DELIVERED_BY_CARRIER` | Παραδόθηκε από τον μεταφορέα | Delivered by the carrier   |
| 7    | `FAILED_DELIVERY`      | Αποτυχία παράδοσης           | Failed delivery            |
| 8    | `COMPLETED`            | Ολοκληρώθηκε                 | Completed                  |
| 9    | `IN_TRANSIT_RETURN`    | Σε διακίνηση (Επιστροφή)     | In transit (return), v2.0.2 |

### DeliveryEventType

| Code                     | Case                       | Label                  | English                        |
|--------------------------|----------------------------|------------------------|--------------------------------|
| `RegisterTransfer`       | `REGISTER_TRANSFER`        | Έναρξη διακίνησης      | Movement start / transshipment |
| `ConfirmOutcome`         | `CONFIRM_OUTCOME`          | Επιβεβαίωση παραλαβής  | Receipt confirmed              |
| `Rejection`              | `REJECTION`                | Απόρριψη               | Rejection                      |
| `ConfirmReturn`          | `CONFIRM_RETURN`           | Επιβεβαίωση επιστροφής | Return confirmed, v2.0.2       |
| `RegisterTransferReturn` | `REGISTER_TRANSFER_RETURN` | Επιστροφή διακίνησης   | Return movement, v2.0.2        |

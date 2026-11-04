# Statements <Badge type="tip" text="Provider only" />

A statement declares that an entity issues its invoices through a provider or as a self-provider.
The provider sends it to myDATA, the entity accepts it, and the provider can recall it later.

> [!WARNING]
> Provider channel only. On an ERP connection every call on this page throws `UnsupportedChannelException`
> and sends nothing.

| Call                                    | Method | Does                                       |
|-----------------------------------------|--------|--------------------------------------------|
| [SendStatement](#sendstatement)         | POST   | Sends a new statement                      |
| [RecallStatement](#recallstatement)     | POST   | Recalls a statement, partly or fully       |
| [RequestStatements](#requeststatements) | GET    | Lists the statements the provider sent     |

## SendStatement

```shell
# production
https://mydatapi.aade.gr/myDataProvider/SendStatement

# development
https://mydataapidev.aade.gr/myDataProvider/SendStatement
```

```php
use Firebed\AadeMyData\Enums\Statements\LiableUserCategory;
use Firebed\AadeMyData\Enums\Statements\ProviderType;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Statements\SendStatement;
use Firebed\AadeMyData\Models\Statements\Statement;

MyDataRequest::init($userId, $subscriptionKey, 'dev', is_provider: true);

$statement = (new Statement)
    ->setEntityVatNumber('888888888')
    ->setLiableUserCategory(LiableUserCategory::PROVIDER)
    ->setProviderType(ProviderType::PROVIDER)
    ->setIsB2BTransactions(true)
    ->setIsB2CTransactions(true)
    ->setProviderVatNumber('999999999')
    ->setProviderLicenceNumber('LICENCE-123')
    ->setProviderContractNumber('111041')
    ->setProviderContractConclusionDate('2026-07-26T09:00:00Z')
    ->setProviderContractActivationDate('2026-08-01T00:00:00Z')
    ->setIssueStartDate('2026-08-01');

$response = (new SendStatement)->handle($statement)->first();

if ($response->isSuccessful()) {
    $statementId = $response->getStatementId();
} else {
    foreach ($response->getErrors() ?? [] as $error) {
        echo $error->getCode().': '.$error->getMessage().PHP_EOL;
    }
}
```

`handle()` takes a `Statement` or a `StatementDoc` and returns a [`StatementResponseDoc`](#responses).

### Statement fields

| Field                            | Required | Description                                                       |
|----------------------------------|----------|-------------------------------------------------------------------|
| `statementId`                    | No       | Statement ID. Set by myDATA                                       |
| `submissionDateTime`             | No       | Submission date. Set by myDATA                                    |
| `entityVatNumber`                | Yes      | VAT number of the liable entity                                   |
| `liableUserCategory`             | Yes      | [LiableUserCategory](#liableusercategory)                         |
| `providerType`                   | Yes      | [ProviderType](#providertype)                                     |
| `isB2BTransactions`              | No*      | B2B transactions                                                  |
| `isB2CTransactions`              | No*      | B2C transactions                                                  |
| `isB2GTransactions`              | No*      | B2G transactions                                                  |
| `providerVatNumber`              | Yes      | Provider VAT number                                               |
| `providerLicenceNumber`          | Yes      | Provider licence number                                           |
| `providerContractNumber`         | Yes      | Provider contract number                                          |
| `providerContractConclusionDate` | Yes      | Date the entity signed the contract. UTC, `yyyy-MM-ddTHH:mm:ssZ`  |
| `providerContractActivationDate` | Yes      | Date the contract takes effect. UTC, `yyyy-MM-ddTHH:mm:ssZ`       |
| `issueStartDate`                 | No       | First day of issuing. `YYYY-MM-DD`                                |
| `issueStopDate`                  | No       | Last day of issuing. `YYYY-MM-DD`                                 |
| `internetProvider`               | No       | Internet provider of the issuing entity                           |
| `internetProviderContractNumber` | No       | Contract number with the internet provider                        |
| `internetProviderContractDate`   | No       | Contract date with the internet provider. UTC, `yyyy-MM-ddTHH:mm:ssZ` |

\* Send at least one of `isB2BTransactions`, `isB2CTransactions`, `isB2GTransactions`.

Each field has a getter (`getEntityVatNumber()`). The fields you send also have a setter (`setEntityVatNumber()`).
The enum setters also take the integer code.

## RecallStatement

```shell
# production
https://mydatapi.aade.gr/myDataProvider/RecallStatement?stmtId={stmtId}&entityVatNumber={entityVatNumber}&recallStatus={recallStatus}&recallDate={recallDate}

# development
https://mydataapidev.aade.gr/myDataProvider/RecallStatement?stmtId={stmtId}&entityVatNumber={entityVatNumber}&recallStatus={recallStatus}&recallDate={recallDate}
```

No request body.

```php
use Firebed\AadeMyData\Enums\Statements\RecallStatus;
use Firebed\AadeMyData\Http\Statements\RecallStatement;

// Full recall: cancels the statement
$response = (new RecallStatement)->handle('727', '888888888', RecallStatus::FULL_RECALL)->first();

// Partial recall: needs a recall date
$response = (new RecallStatement)->handle(
    statementId: '727',
    entityVatNumber: '888888888',
    recallStatus: RecallStatus::PARTIAL_RECALL,
    recallDate: '2026-12-31',
)->first();

if ($response->isSuccessful()) {
    $recallId = $response->getRecallId();
}
```

| Parameter         | Required | Description                                                                 |
|-------------------|----------|-----------------------------------------------------------------------------|
| `statementId`     | Yes      | ID of the statement to recall. Sent as `stmtId`                             |
| `entityVatNumber` | Yes      | VAT number of the liable entity                                             |
| `recallStatus`    | Yes      | [RecallStatus](#recallstatus) or its integer code                           |
| `recallDate`      | No       | `YYYY-MM-DD`. Send it when `recallStatus` is `PARTIAL_RECALL` (1)           |

On success the recall gets its own `recallId` and the statement counts as recalled.
Returns a [`StatementResponseDoc`](#responses).

## RequestStatements

```shell
# production
https://mydatapi.aade.gr/myDataProvider/RequestStatements?stmtId={stmtId}&maxstmtId={maxstmtId}&entityVatNumber={entityVatNumber}

# development
https://mydataapidev.aade.gr/myDataProvider/RequestStatements?stmtId={stmtId}&maxstmtId={maxstmtId}&entityVatNumber={entityVatNumber}
```

Lists the statements the provider sent for one entity.

```php
use Firebed\AadeMyData\Http\Statements\RequestStatements;

$statements = (new RequestStatements)->handle(entityVatNumber: '888888888');

foreach ($statements as $requested) {
    $statement = $requested->getStatement();

    $statement->getStatementId();
    $statement->getLiableUserCategory()?->label();

    if ($requested->isAccepted()) {
        $requested->getAcceptVatNumber();
        $requested->getAcceptDate();
    }

    if ($requested->isRecalled()) {
        foreach ($requested->getRecallStatement() as $recall) {
            $recall->getRecallId();
            $recall->getRecallStatus(); // RecallStatus
            $recall->getTransactionDate();
        }
    }
}
```

| Parameter         | Required | Description                                                       |
|-------------------|----------|-------------------------------------------------------------------|
| `stmtId`          | Yes      | Statement ID (search criterion). Default `''`                     |
| `maxstmtId`       | No       | Return statements with an ID less than or equal to this           |
| `entityVatNumber` | Yes      | VAT number of the liable entity                                   |

Dates in the result are in Greek time (`Europe/Athens`).

`handle()` returns a `RequestedStatementDoc`, a list of `RequestedStatement`:

| Method                 | Returns               | Description                                        |
|------------------------|-----------------------|----------------------------------------------------|
| `getStatement()`       | `?Statement`          | The statement, see [Statement fields](#statement-fields) |
| `getAcceptVatNumber()` | `?string`             | VAT number that accepted the statement             |
| `getAcceptDate()`      | `?string`             | Date the liable entity accepted it                 |
| `getRecallStatement()` | `?RecallStatementDoc` | The recalls, a list of `RecalledStatementType`     |
| `isAccepted()`         | `bool`                | Has an accept date                                 |
| `isRecalled()`         | `bool`                | Has a recall                                       |

`RecalledStatementType`:

| Method                 | Returns                  | Description                                    |
|------------------------|--------------------------|------------------------------------------------|
| `getStatementID()`     | `?int`                   | ID of the recalled statement                   |
| `getEntityVatNumber()` | `?string`                | VAT number of the liable entity                |
| `getRecallId()`        | `?int`                   | ID of the recall                               |
| `getRecallStatus()`    | `RecallStatus\|int\|null` | [RecallStatus](#recallstatus)                  |
| `getRecallDate()`      | `?string`                | Recall date, as you sent it                    |
| `getTransactionDate()` | `?string`                | Date the recall was registered. Set by myDATA  |
| `getRecallVatNumber()` | `?string`                | VAT number that recalled the statement         |

## Responses

`SendStatement` and `RecallStatement` return a `StatementResponseDoc`, a list of `StatementResponse`:

| Method             | Returns   | Description                                                       |
|--------------------|-----------|-------------------------------------------------------------------|
| `isSuccessful()`   | `bool`    | `statusCode` is `Success`                                         |
| `getStatusCode()`  | `?string` | `Success`, `ValidationError`, `TechnicalError`, `XMLSyntaxError`  |
| `getStatementId()` | `?string` | ID of the new statement (SendStatement)                           |
| `getRecallId()`    | `?string` | ID of the recall (RecallStatement)                                |
| `getErrors()`      | `?Errors` | The errors, each with `getCode()` and `getMessage()`              |

## With the MyData client

```php
use Firebed\AadeMyData\Enums\Statements\RecallStatus;
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$responses  = $mydata->sendStatement($statement);
$responses  = $mydata->recallStatement('727', '888888888', RecallStatus::FULL_RECALL);
$statements = $mydata->requestStatements(entityVatNumber: '888888888');
```

## Code lists

`label()` returns the Greek label.

### LiableUserCategory

`Firebed\AadeMyData\Enums\Statements\LiableUserCategory`

| Code | Case            | Label            | English         |
|------|-----------------|------------------|-----------------|
| 1    | `PROVIDER`      | Πάροχος          | Provider        |
| 2    | `SELF_PROVIDER` | ΙδιοΠάροχος      | Self-provider   |
| 3    | `ENTITY`        | Υπόχρεη Οντότητα | Liable entity   |

> [!NOTE]
> The AADE spec and the XSD accept only 1 and 2 for `liableUserCategory`.

### ProviderType

`Firebed\AadeMyData\Enums\Statements\ProviderType`

| Code | Case            | Label       | English       |
|------|-----------------|-------------|---------------|
| 1    | `PROVIDER`      | Πάροχος     | Provider      |
| 2    | `SELF_PROVIDER` | ΙδιοΠάροχος | Self-provider |

### RecallStatus

`Firebed\AadeMyData\Enums\Statements\RecallStatus`

| Code | Case             | Label                                  | English                                   |
|------|------------------|----------------------------------------|-------------------------------------------|
| 1    | `PARTIAL_RECALL` | Μερική Ανάκληση                        | Partial recall. Needs `recallDate`        |
| 2    | `FULL_RECALL`    | Πλήρης Ανάκληση (Ακύρωση της Δήλωσης)  | Full recall (cancels the statement)       |

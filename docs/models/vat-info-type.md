# VatInfo

`RequestVatInfo` returns a `RequestedVatInfo`. Its `getVatInfo()` returns a list of `VatInfo`, one per invoice
(or per day with `groupedPerDay: true`), or `null` when there are none.

```php
use Firebed\AadeMyData\Http\RequestVatInfo;

$result = (new RequestVatInfo())->handle(dateFrom: '01/01/2026', dateTo: '31/01/2026');

foreach ($result->getVatInfo() ?? [] as $info) {
    echo $info->getMark();
    echo $info->isCancelled() ? 'cancelled' : 'active';
    echo $info->getVat301();
}

$token = $result->getContinuationToken(); // next page, see ContinuationToken
```

| Field              | Getter                     | Type     | Description |
|--------------------|----------------------------|----------|-------------|
| Mark               | `getMark()`                | `string` | MARK of the invoice |
| IsCancelled        | `isCancelled()`            | `bool`   | The invoice is cancelled |
| IssueDate          | `getIssueDate()`           | `string` | Issue date |
| Vat301 ... Vat306  | `getVat301()` ...          | `float`  | VAT amount of field 301 ... 306 |
| Vat331 ... Vat336  | `getVat331()` ...          | `float`  | VAT amount of field 331 ... 336 |
| Vat361 ... Vat366  | `getVat361()` ...          | `float`  | VAT amount of field 361 ... 366 |
| Vat381 ... Vat386  | `getVat381()` ...          | `float`  | VAT amount of field 381 ... 386 |
| Vat342, Vat345, Vat348, Vat349 | `getVat342()` ... | `float`  | VAT amount of field 342, 345, 348, 349 |
| Vat310             | `getVat310()`              | `float`  | VAT amount of field 310 |
| Vat402, Vat407, Vat411 | `getVat402()` ...      | `float`  | VAT amount of field 402, 407, 411 |
| Vat422, Vat423     | `getVat422()`, `getVat423()` | `float` | VAT amount of field 422, 423 |
| VatUnclassified361 | `getVatUnclassified361()`  | `float`  | VAT of field 361 not yet classified |
| VatUnclassified381 | `getVatUnclassified381()`  | `float`  | VAT of field 381 not yet classified |

Each `Vat...` field is the VAT amount for that field of the VAT return (input and output).

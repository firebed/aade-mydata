# E3Info

`RequestE3Info` returns a `RequestedE3Info`. Its `getE3Info()` returns a list of `E3Info` rows, or `null` when there
are none.

```php
use Firebed\AadeMyData\Http\RequestE3Info;

$result = (new RequestE3Info())->handle(dateFrom: '01/01/2026', dateTo: '31/01/2026');

foreach ($result->getE3Info() ?? [] as $row) {
    echo $row->getMark();
    echo $row->getClassType();     // e.g. E3_561_001
    echo $row->getClassCategory(); // e.g. category1_1
    echo $row->getClassValue();
}

$token = $result->getContinuationToken(); // next page, see ContinuationToken
```

| Field            | Getter                 | Type     | Description |
|------------------|------------------------|----------|-------------|
| V_Afm            | `getVat()`             | `string` | VAT number |
| V_Mark           | `getMark()`            | `string` | MARK of the invoice |
| vBook            | `get('vBook')`         | `string` | Book (income or expenses) |
| IsCancelled      | `get('IsCancelled')`   | `bool`   | The invoice is cancelled |
| IssueDate        | `getIssueDate()`       | `string` | Issue date |
| V_Class_Category | `getClassCategory()`   | `string` | Classification category, see [income](../appendix/income-classifications) and [expense](../appendix/expenses-classifications) classifications |
| V_Class_Type     | `getClassType()`       | `string` | Classification type |
| V_Class_Value    | `getClassValue()`      | `string` | Classification amount |

The classification fields are plain strings, not enums.

# BookInfo

`RequestMyIncome` and `RequestMyExpenses` return a `RequestedBookInfo`. Its `getBookInfo()` returns a list of
`BookInfo` rows, or `null` when there are none.

```php
use Firebed\AadeMyData\Http\RequestMyIncome;

$result = (new RequestMyIncome())->handle(dateFrom: '01/01/2026', dateTo: '31/01/2026');

foreach ($result->getBookInfo() ?? [] as $row) {
    echo $row->getInvType()?->label();
    echo $row->getNetValue();
    echo $row->getVatAmount();
}

$token = $result->getContinuationToken(); // next page, see ContinuationToken
```

| Field             | Getter                   | Type                                                   | Description |
|-------------------|--------------------------|--------------------------------------------------------|-------------|
| counterVatNumber  | `getCounterVatNumber()`  | `string`                                               | VAT number of the counterpart |
| issueDate         | `getIssueDate()`         | `string`                                               | Issue date |
| invType           | `getInvType()`           | [`InvoiceType`](../appendix/invoice-types)             | Invoice type |
| selfpricing       | `getSelfPricing()`       | `bool`                                                 | Self-billing |
| invoiceDetailType | `getInvoiceDetailType()` | [`InvoiceDetailType`](../appendix/invoice-detail-types) | Marking |
| netValue          | `getNetValue()`          | `string`                                               | Net value |
| vatAmount         | `getVatAmount()`         | `string`                                               | VAT amount |
| withheldAmount    | `getWithheldAmount()`    | `string`                                               | Withholding tax amount |
| otherTaxesAmount  | `getOtherTaxesAmount()`  | `string`                                               | Other taxes amount |
| stampDutyAmount   | `getStampDutyAmount()`   | `string`                                               | Stamp duty amount |
| feesAmount        | `getFeesAmount()`        | `string`                                               | Fees amount |
| deductionsAmount  | `getDeductionsAmount()`  | `string`                                               | Deductions amount |
| thirdPartyAmount  | `getThirdPartyAmount()`  | `string`                                               | Amount on behalf of third parties |
| grossValue        | `getGrossValue()`        | `string`                                               | Gross value |
| count             | `getCount()`             | `string`                                               | Number of invoices in the row |
| minMark           | `getMinMark()`           | `string`                                               | Smallest MARK in the row |
| maxMark           | `getMaxMark()`           | `string`                                               | Largest MARK in the row |

## Notes

- Each row is one unique set of `counterVatNumber`, `issueDate`, `invType` and the reference VAT number.
- `minMark` and `maxMark` are the smallest and largest MARK of the invoices in the row.
- Self-billing invoices come in a separate row, with `selfpricing` set.
- Rows of type 1.5 invoices (settlement, third-party fees) come in separate rows.

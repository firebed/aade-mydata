# Response

Every send or cancel call (`SendInvoices`, `SendIncomeClassification`, `SendExpensesClassification`,
`SendPaymentsMethod`, `CancelInvoice`, ...) returns a `ResponseDoc`. It holds one `Response` per entity you sent,
in the same order.

```php
use Firebed\AadeMyData\Http\SendInvoices;

$responses = (new SendInvoices())->handle($invoices);

foreach ($responses as $response) {
    if ($response->isSuccessful()) {
        $mark = $response->getInvoiceMark();
        $uid = $response->getInvoiceUid();
        $qrUrl = $response->getQrUrl();
    } else {
        foreach ($response->getErrors() ?? [] as $error) {
            echo $error->getCode() . ': ' . $error->getMessage();
        }
    }
}
```

| Field              | Getter                     | Type                                        | Description |
|--------------------|----------------------------|---------------------------------------------|-------------|
| index              | `getIndex()`               | `int`                                       | Position of the entity in the request |
| statusCode         | `getStatusCode()`          | `string`                                    | `Success`, `ValidationError`, `TechnicalError` or `XMLSyntaxError` |
| invoiceUid         | `getInvoiceUid()`          | `string`                                    | Invoice uid (40 characters). Only when you sent an invoice |
| invoiceMark        | `getInvoiceMark()`         | `string`                                    | MARK of the invoice you sent, or of the invoice your classifications belong to |
| classificationMark | `getClassificationMark()`  | `string`                                    | MARK of the classification. Only when you sent classifications |
| cancellationMark   | `getCancellationMark()`    | `string`                                    | MARK of the cancellation. Only when you cancelled an invoice |
| paymentMethodMark  | `getPaymentMethodMark()`   | `string`                                    | MARK of the payment methods. Only when you sent payment methods |
| authenticationCode | `getAuthenticationCode()`  | `string`                                    | Authentication code. Provider channel only |
| qrUrl              | `getQrUrl()`               | `string`                                    | URL for the invoice QR code. Only for invoice types 1.1 to 11.5 |
| receptionProviders | `getReceptionProviders()`  | `ReceptionProviders`                        | Providers of the recipient, see [ReceiverInfoDoc](receiver-info-doc-type). Provider channel |
| receptionEmails    | `getReceptionEmails()`     | `ReceptionEmails`                           | Emails of the recipient, see [ReceiverInfoDoc](receiver-info-doc-type). Provider channel |
| errors             | `getErrors()`              | [`Errors`](error-type)                      | List of errors |

| Method            | Description |
|-------------------|-------------|
| `isSuccessful()`  | `statusCode` is `Success` |
| `hasErrors()`     | The response has at least one error |
| `getDuplicate()`  | The invoice myDATA already holds under this uid (error 228), else `null`. See [Duplicate](duplicate-type) |

## Notes

- `statusCode` tells you if the entity was accepted. On success you get the fields that match what you sent:
  `invoiceUid` and `invoiceMark`, `classificationMark` or `cancellationMark`.
- On failure, `statusCode` is the error kind and `errors` lists the errors. All errors of one entity have the same kind.

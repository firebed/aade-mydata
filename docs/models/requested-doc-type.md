# RequestedDoc

`RequestDocs` and `RequestTransmittedDocs` return a `RequestedDoc`. It holds the invoices, cancellations,
classifications and payment methods with a MARK greater than the one you asked for.

```php
use Firebed\AadeMyData\Http\RequestDocs;

$doc = (new RequestDocs())->handle(mark: '0', dateFrom: '01/01/2026', dateTo: '31/01/2026');

foreach ($doc->getInvoices() ?? [] as $invoice) {
    echo $invoice->getMark();
}

foreach ($doc->getCancelledInvoices() ?? [] as $cancelled) {
    echo $cancelled->getInvoiceMark();
}
```

| Field                      | Getter                         | Type | Description |
|----------------------------|--------------------------------|------|-------------|
| continuationToken          | `getContinuationToken()`       | [ContinuationToken](continuation-token-type) | Key to the next page, `null` on the last page |
| invoicesDoc                | `getInvoices()`                | `InvoicesDoc` of [Invoice](invoice-type) | Invoices |
| cancelledInvoicesDoc       | `getCancelledInvoices()`       | `CancelledInvoicesDoc` of [CancelledInvoice](cancelled-invoice-type) | Cancellations |
| incomeClassificationsDoc   | `getIncomeClassifications()`   | `IncomeClassificationsDoc` of [InvoiceIncomeClassification](invoice-income-classification-type) | Income classifications |
| expensesClassificationsDoc | `getExpensesClassifications()` | `ExpensesClassificationsDoc` of [InvoiceExpensesClassification](invoice-expenses-classification-type) | Expense classifications |
| paymentMethodsDoc          | `getPaymentMethods()`          | `PaymentMethodsDoc` of [PaymentMethod](payment-method-type) | Payment methods |

Each getter returns `null` when myDATA sent no such list. The lists are collections: you can `foreach` them,
`count()` them, and call `all()`, `first()` and `last()`.

> [!TIP]
> To read all pages, use `pages()` or `invoices()` instead of `handle()`:
>
> ```php
> foreach ((new RequestDocs())->invoices(mark: '0') as $invoice) {
>     // every invoice of every page
> }
> ```

# SendUnsignedInvoices <Badge type="tip" text="Provider only" />

```shell
# production
https://mydatapi.aade.gr/myDataProvider/SendUnsignedInvoices

# development
https://mydataapidev.aade.gr/myDataProvider/SendUnsignedInvoices
```

Sends invoices "under issue" (official marking: «Υπό Έκδοση»). Same body as [SendInvoices](./send-invoices).
The `InvoicesDoc` schema has no field for this marking, so the endpoint itself is the marking.

> [!WARNING]
> Provider channel only. On an ERP connection the call throws `UnsupportedChannelException` and sends nothing.

```php
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendUnsignedInvoices;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

MyDataRequest::init($userId, $subscriptionKey, 'dev', is_provider: true);

// The invoice carries the Provider's Signature in its POS payment
$invoice->addPaymentMethod(
    (new PaymentMethodDetail)
        ->setType(PaymentType::POS)
        ->setAmount(12.40)
        ->setTid('12345678')
        ->setProvidersSignature($signingAuthor, $signature)
);

$responses = (new SendUnsignedInvoices)->handle($invoice);
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$responses = $mydata->sendUnsignedInvoices($invoice);
```

## Parameters

| Parameter  | Required | Description                                                       |
|------------|----------|-------------------------------------------------------------------|
| `invoices` | Yes      | An `Invoice`, a list of `Invoice`, or an `InvoicesDoc`            |

## Result

`handle()` returns a `ResponseDoc`, a list of [Response](../models/response-type) objects, as in SendInvoices.

## When to use it

In a POS or instant payment (e.g. IRIS) through a provider, the provider creates the Provider's Signature for the
invoice before the invoice is issued. When that signature is never matched with an issued invoice, the decisions
below may require you to transmit the invoice under issue. When you must transmit and when you only need to record is
set by A.1155/2023, as amended by A.1160/2025.

### What the decisions say

Unofficial translations. Follow the links for the Greek text.

Art. 4 of the codified [A.1155/2023](https://www.taxheaven.gr/circulars/45062/a-1155-2023) (cards / POS):

> For Provider's Signatures / ECR Tokens that the cash register system did not match with invoices and a Unique
> Payment ID within the set time limit, the provider (or the cash register with ERP software) records and transmits
> to AADE, for simultaneous transactions, the invoices under issue that the payment service user did not transmit.
> These invoices are transmitted to AADE marked "Under issue".

Art. 4A par. 2 (instant payments, e.g. IRIS), added by [A.1160/2025](https://www.taxheaven.gr/circulars/51494/a-1160-2025)
(Government Gazette B' 6123/17.11.2025), says the same for Unique Combined Keys not matched with invoices and a
Unique Payment ID.

A.1160/2025 also replaced art. 3 par. 2(c), for transactions that do not complete:

> If a card or account-to-account instant payment (such as IRIS payments) does not complete, the payment instrument
> automatically rejects the Provider's Signature for that invoice within sixty (60) hours of its creation, and the
> provider must record this rejection.
>
> If the payment instrument does not reject the Provider's Signature within that period, although it was not matched
> with a Unique Payment ID, the provider must record and document this separately.

[A.1170/2023](https://www.taxheaven.gr/circulars/45181/a-1170-2023) (art. 7, item 13) says that in simultaneous POS
transactions, invoices are transmitted before they are issued, marked "Under issue".
[A.1074/2024](https://www.taxheaven.gr/circulars/46955/a-1074-2024) does not cover invoices under issue.

### Our reading

The decisions do not say this explicitly. Confirm it with AADE before you rely on it.

| Case                                                                                    | Provider duty                                                                 |
|-----------------------------------------------------------------------------------------|-------------------------------------------------------------------------------|
| The payment did not complete (declined, cancelled) and the payment instrument rejected the signature | Record the rejection. No transmission (art. 3 par. 2c)            |
| The signature was neither used nor rejected within 60 hours                             | Record and document it separately (art. 3 par. 2c)                            |
| The payment completed (there is a Unique Payment ID), but no invoice was issued         | Transmit the invoice under issue with `SendUnsignedInvoices` (art. 4 / 4A par. 2) |

So a cancelled signature leads to recording, not transmission. Recording is also a legal duty.

This reading assumes the provider knows whether the payment completed. That is true only when the provider itself
accepts the instant payment through the Electronic Payments System (e.g. DIAS), as Annex B describes. Then it gets the
result (`PaymentResultResponse`, with `statusID` on success). When the payment, card or IRIS, completes on a payment
instrument (terminal, NSP), the result goes to the cash register system, not to the provider. The provider only knows
whether the signature was used in an invoice, cancelled, or expired unused.

Open questions to AADE:

1. How does the provider learn that a signature was rejected or a payment completed, when the payment completes on a
   payment instrument and not through the provider?
2. Without that information, do you transmit a signature that expired unused with `SendUnsignedInvoices`, or is
   recording and documenting it enough?
3. Is the reading above right (cancelled or rejected: record only; paid without an invoice: transmit)?

## What myDATA answers

Observed on the provider sandbox (November 2026):

- It answers `Success` with the `invoiceUid` and an `invoiceMark` from a separate, shorter range (8 digits),
  without `authenticationCode` and `qrUrl`.
- It accepted the same invoice twice, with a new MARK each time. Sending each invoice once is your job.
- A later `SendInvoices` with the same UID was accepted as a normal invoice.
- It accepted an invoice dated yesterday.
- It did not check the signature (it also accepted a cash payment with no signature). Production may check more.
- [RequestProviderDocs](./request-provider-docs) does not list these invoices.
- It accepted a minimal body with only the data of a signature: issuer (VAT number, country, branch), series, number,
  date, type, one line with the totals (net value, VAT category, VAT), summary, and a POS payment with `tid` and
  signature. No counterpart, currency or income classifications. This worked for types 11.1 and 1.1.
  The same 1.1 body sent to the provider `SendInvoices` failed only for the counterpart and the currency
  (error 204: "Counterpart is mandatory for this invoice type", "Currency is mandatory for this invoice type").
  Income classifications are not required on the provider channel anyway. Whether the minimal body is legally enough
  is not something the sandbox can tell.

> [!IMPORTANT]
> The list above is sandbox observations, not an AADE spec. On time limits: A.1160/2025 says the payment instrument
> rejects the signature of a transaction that did not complete within 60 hours of its creation. For restaurant
> transactions the rejection happens after two hours (art. 6 par. 4 of A.1155/2023).

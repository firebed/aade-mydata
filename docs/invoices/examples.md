# Invoice examples

Each page builds one invoice, calls `summarizeInvoice()` and shows how to send it.
The issuer VAT number is `888888888` and the recipient is `999999999`.

> [!WARNING]
> Use valid VAT numbers for the issuer, the recipient and any other party, in production and in the sandbox.

| Example | Type | Shows |
|---|---|---|
| [Sales invoice](./sale-invoice) | `1.1` | Two lines, withholding tax per invoice |
| [Service invoice](./service-invoice) | `2.1` | Services, withholding tax |
| [Taxes per line](./taxes-per-row) | `1.1` | Withholding tax on the line |
| [Invoice and delivery note](./delivery-note-invoice) | `1.1` | `isDeliveryNote`, loading and delivery addresses |
| [Delivery note](./delivery-invoice) | `9.3` | Goods movement, zero values, `category3` |
| [Credit invoice (correlated)](./credit-invoice) | `5.1` | Credit linked to an invoice MARK |
| [Credit invoice (not correlated)](./credit-invoice-uncorrelated) | `5.2` | Credit with no MARK |
| [Retail receipt](./retail-invoice) | `11.1` | No counterpart |
| [Retail service receipt](./retail-service-invoice) | `11.2` | Services, no counterpart |
| [Retail credit note](./credit-retail-invoice) | `11.4` | Refund on a retail receipt |
| [Intra-community invoice](./foreign-invoice) | `1.2` | EU recipient, VAT exemption |
| [Export invoice](./export-invoice) | `1.3` | Non-EU recipient, VAT exemption |
| [Climate resilience fee](./climate-resilience-fee) | `8.2` | Zero net value, fee in other taxes |
| [Restaurant order slip](./restaurant-order-slip) | `8.6` | Provider only, VAT-exempt line, closing and cancelling slips |
| [Complete example](./complete-example) | `1.1` | The sales invoice in one file |

Check every example against your own case before you use it.

## Keep the request and response XML

`SendInvoices` keeps the XML it sent and the XML myDATA returned, as `DOMDocument` or as a string.

> [!TIP]
> In production, save both XML documents (in files or in your database). You will need them to debug a rejected invoice.

### One invoice

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;

try {
    $request = new SendInvoices();
    $responses = $request->handle($invoice);

    $requestXml = $request->getRequestXml();   // or $request->getRequestDom()
    $responseXml = $request->getResponseXml(); // or $request->getResponseDom()

    $response = $responses->first();
    if ($response->isSuccessful()) {
        $uid = $response->getInvoiceUid();
        $mark = $response->getInvoiceMark();
        $qrUrl = $response->getQrUrl();

        // INSERT INTO invoices (..., uid, mark, qr_url, request_xml, response_xml)
    } else {
        $errors = $response->getErrors()->toArray();

        // INSERT INTO logs (..., errors, request_xml, response_xml)
    }
} catch (MyDataException $e) {
    echo 'Communication error: '.$e->getMessage();
}
```

### Many invoices

When you send many invoices in one request, myDATA returns one XML with a response for each invoice.
Use the response index (it starts at 1) to find the invoice and its part of each XML.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;

try {
    $invoices = [$invoice1, $invoice2, $invoice3];

    $request = new SendInvoices();
    $responses = $request->handle($invoices);

    foreach ($responses as $response) {
        $index = $response->getIndex() - 1;

        $invoice = $invoices[$index];
        $requestXml = $request->getRequestElement('invoice', $index);
        $responseXml = $request->getResponseElement('response', $index);

        if ($response->isSuccessful()) {
            $uid = $response->getInvoiceUid();
            $mark = $response->getInvoiceMark();
            $qrUrl = $response->getQrUrl();

            // INSERT INTO invoices (..., uid, mark, qr_url, request_xml, response_xml)
        } else {
            $errors = $response->getErrors()->toArray();

            // INSERT INTO logs (..., errors, request_xml, response_xml)
        }
    }
} catch (MyDataException $e) {
    echo 'Communication error: '.$e->getMessage();
}
```

> [!WARNING]
> Check each response. In one request some invoices can succeed and others fail.

More on sending: [SendInvoices](../http/send-invoices).

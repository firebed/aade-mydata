# Εγκατάσταση

## Εγκατάσταση μέσω του Composer

Για εγκατάσταση μέσω του Composer, εκτελέστε την ακόλουθη εντολή:
```shell
composer require firebed/aade-mydata
```

### Απαιτήσεις

```json
{
    "require": {
      "php": "^8.1",
      "ext-dom": "*",
      "guzzlehttp/guzzle": "^7.0.1"
    }
}
```

## Χειροκίνητη εγκατάσταση

Σε περίπτωση που η εγκατάσταση γίνεται χειροκίνητα και όχι μέσω του composer θα πρέπει να φορτώσετε χειροκίνητα και τα 
απαραίτητα αρχεία με τη χρήση της μεθόδου <code>[spl_autoload_register](https://www.php.net/manual/en/function.spl-autoload-register.php)</code> (δε συνιστάται).

## Παράμετροι εγκατάστασης

Αρχικά θα χρειαστείτε ένα αναγνωριστικό χρήστη (***user id***) και ένα κλειδί συνδρομής (***subscription key***).
Μπορείτε να λάβετε αυτά τα διαπιστευτήρια με την εγγραφή σας στο mydata rest api.

## Σύνδεσμοι

- [Δοκιμαστικό περιβάλλον](https://www.aade.gr/mydata/dokimastiko-periballon)
- [Εγγραφή στο δοκιμαστικό περιβάλλον](https://mydata-dev-register.azurewebsites.net/)
- [Εγγραφή στο παραγωγικό περιβάλλον](https://www.aade.gr/mydata)
- [Επίσημη τεκμηρίωση ΑΑΔΕ myDATA (PDF v1.0.12)](https://www.aade.gr/sites/default/files/2025-11/myDATA%20API%20Documentation%20v1.0.12_official_erp.pdf)

## Αρχικοποίηση

Αφού έχετε το αναγνωριστικό χρήστη και το κλειδί συνδρομής, χρησιμοποιήστε τον ακόλουθο κώδικα για να ορίσετε το
περιβάλλον και τα διαπιστευτήρια:

```php
use Firebed\AadeMyData\Http\MyDataRequest;

$env = "dev"; // For production use "prod"
$user_id = "your-user-id";
$subscription_key = "your-subscription-key";

MyDataRequest::setEnvironment($env);
MyDataRequest::setCredentials($user_id, $subscription_key);
```

Ή εναλλακτικά, μπορείτε να ορίσετε το περιβάλλον και τα διαπιστευτήρια μέσω της μεθόδου `init`:

```php
MyDataRequest::init($user_id, $subscription_key, $env);
```

Για το στάδιο της ανάπτυξη, μπορεί να χρειαστεί να απενεργοποιήσετε την επαλήθευση πελάτη εάν δεν χρησιμοποιείτε ***https***:

```php
MyDataRequest::verifyClient(false);
```

## Διαθέσιμες λειτουργίες
- Λήψη Βασικών Στοιχείων Μητρώου ΑΦΜ ([SearchVat](./http/search-vat))
- Αποστολή παραστατικών ([SendInvoices](./http/send-invoices))
- Ακύρωση παραστατικού ([CancelInvoice](./http/cancel-invoice))
- Λήψη παραστατικών ([RequestDocs](./http/request-docs))
- Λήψη εκδοθέντων παραστατικών ([RequestTransmittedDocs](./http/request-transmitted-docs))
- Λήψη εσόδων ([RequestMyIncome](./http/request-my-income))
- Λήψη εξόδων ([RequestMyExpenses](./http/request-my-expenses))
- Λήψη αναφορών ΦΠΑ ([RequestVatInfo](./http/request-vat-info))
- Λήψη αναφορών Ε3 ([RequestE3Info](./http/request-e3-info))
- Διαβίβαση πληρωμών ([SendPaymentsMethod](./http/send-payments-method))
- Διαβίβαση χαρακτηρισμών εσόδων ([SendIncomeClassification](./http/send-income-classification))
- Διαβίβαση χαρακτηρισμών εξόδων ([SendExpensesClassification](./http/send-expenses-classification))
- Ακύρωση δελτίων αποστολής ([CancelDeliveryNote](./http/cancel-delivery-note))

## Αρχεία XSD

- [ConfirmDeliveryOutcome-v2.0.2.xsd](../xsd/ConfirmDeliveryOutcome-v2.0.2.xsd)
- [ConfirmDeliveryReturn-v2.0.2.xsd](../xsd/ConfirmDeliveryReturn-v2.0.2.xsd)
- [expensesClassification-v2.0.2.xsd](../xsd/expensesClassification-v2.0.2.xsd)
- [GenerateGroupQRCode-v2.0.2.xsd](../xsd/GenerateGroupQRCode-v2.0.2.xsd)
- [GenerateGroupQRCodeResponse-v2.0.2.xsd](../xsd/GenerateGroupQRCodeResponse-v2.0.2.xsd)
- [GetDeliveryStatusResponse-v2.0.2.xsd](../xsd/GetDeliveryStatusResponse-v2.0.2.xsd)
- [incomeClassification-v2.0.2.xsd](../xsd/incomeClassification-v2.0.2.xsd)
- [InvoicesDoc-v2.0.2.xsd](../xsd/InvoicesDoc-v2.0.2.xsd)
- [InvoicesDoc-v2.0.2_aade_detailed.xsd](../xsd/InvoicesDoc-v2.0.2_aade_detailed.xsd)
- [paymentMethods-v2.0.2.xsd](../xsd/paymentMethods-v2.0.2.xsd)
- [RegisterTransfer-v2.0.2.xsd](../xsd/RegisterTransfer-v2.0.2.xsd)
- [RejectDeliveryNote-v2.0.2.xsd](../xsd/RejectDeliveryNote-v2.0.2.xsd)
- [RequestE3InfoResponse-v2.0.2.xsd](../xsd/RequestE3InfoResponse-v2.0.2.xsd)
- [requestedInvoicesDoc-v2.0.2.xsd](../xsd/requestedInvoicesDoc-v2.0.2.xsd)
- [RequestedProviderDoc-v2.0.2.xsd](../xsd/RequestedProviderDoc-v2.0.2.xsd)
- [RequestedStatementDoc-v2.0.2.xsd](../xsd/RequestedStatementDoc-v2.0.2.xsd)
- [RequestGroupQRDetailsResponse-v2.0.2.xsd](../xsd/RequestGroupQRDetailsResponse-v2.0.2.xsd)
- [RequestVatInfoResponse-v2.0.2.xsd](../xsd/RequestVatInfoResponse-v2.0.2.xsd)
- [response-v2.0.2.xsd](../xsd/response-v2.0.2.xsd)
- [SendStatement-v2.0.2.xsd](../xsd/SendStatement-v2.0.2.xsd)
- [SimpleTypes-v2.0.2.xsd](../xsd/SimpleTypes-v2.0.2.xsd)
- [TransportTypes-v2.0.2.xsd](../xsd/TransportTypes-v2.0.2.xsd)
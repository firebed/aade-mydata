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
- [Επίσημη τεκμηρίωση ΑΑΔΕ myDATA (PDF v1.0.8)](https://aade.gr/sites/default/files/2024-02/myDATA%20API%20Documentation%20v1.0.8_preofficial_erp_1.pdf)

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
- Διαβίβαση παραστατικών ([SendInvoices](./http/send-invoices))
- Διαβίβαση χαρακτηρισμών εσόδων ([SendIncomeClassification](./http/send-income-classification))
- Διαβίβαση χαρακτηρισμών εξόδων ([SendExpensesClassification](./http/send-expenses-classification))
- Διαβίβαση πληρωμών ([SendPaymentsMethod](./http/send-payments-method))
- Ακύρωση παραστατικού ([CancelInvoice](./http/cancel-invoice))
- Λήψη παραστατικών ([RequestDocs](./http/request-docs))
- Λήψη εκδοθέντων παραστατικών ([RequestTransmittedDocs](./http/request-transmitted-docs))
- Λήψη εσόδων ([RequestMyIncome](./http/request-my-income))
- Λήψη εξόδων ([RequestMyExpenses](./http/request-my-expenses))
- Λήψη αναφορών ΦΠΑ ([RequestVatInfo](./http/request-vat-info))

## Αρχεία XSD

- [expensesClassification-v1.0.8.xsd](../xsd/expensesClassification-v1.0.8.xsd)
- [incomeClassification-v1.0.8.xsd](../xsd/incomeClassification-v1.0.8.xsd)
- [InvoicesDoc-v1.0.8.xsd](../xsd/InvoicesDoc-v1.0.8.xsd)
- [paymentsMethod-v1.0.8.xsd](../xsd/paymentMethods-v1.0.8.xsd)
- [requestedInvoicesDoc-v1.0.8.xsd](../xsd/requestedInvoicesDoc-v1.0.8.xsd)
- [RequestedProviderDoc-v1.0.8.xsd](../xsd/RequestedProviderDoc-v1.0.8.xsd)
- [RequestVatInfo Response-v1.0.8.xsd](../xsd/RequestVatInfo%20Response.xsd)
- [response-v1.0.8.xsd](../xsd/response-v1.0.8.xsd)
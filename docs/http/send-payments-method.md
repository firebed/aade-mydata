# Διαβίβαση πληρωμών - SendPaymentsMethod

```shell
# production
https://mydatapi.aade.gr/myDATA/SendPaymentsMethod

# development
https://mydataapidev.aade.gr/SendPaymentsMethod
```

Με τη μέθοδο αυτή μπορείτε να υποβάλλετε έναν ή περισσότερους τρόπους
πληρωμής, που θα αντιστοιχηθούν σε ήδη υποβεβλημένα παραστατικά.

> [!NOTE]
> **Κατά τη χρήση της μεθόδου, τουλάχιστον ένα αντικείμενο `PaymentMethodDetail`
> ανά παραστατικό πρέπει να είναι τύπου POS**.

> [!NOTE]
> **Το σύνολο των ποσών `amount` ανά αντικείμενο `PaymentMethod` πρέπει να 
> ισούται με το `totalGrossValue` του παραστατικού στο οποίο αντιστοιχεί το
> `invoiceMark`.**

## Παράδειγμα

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\ECRToken;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Enums\PaymentMethod as Payments;

try {
    $ecrToken = new ECRToken('signing-author-id', 'session-number');
    
    $details = new PaymentMethodDetail();
    $details->setType(Payments::METHOD_7);
    $details->setAmount(1000);
    $details->setECRToken($ecrToken);
    
    $paymentMethod = new PaymentMethod();
    $paymentMethod->setInvoiceMark('1234567890'); // Valid invoice mark
    $paymentMethod->addPaymentMethodDetails($details);
    
    $send = new SendPaymentsMethod();
    $responses = $send->handle($paymentMethod);
    
    // ... handle responses
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```
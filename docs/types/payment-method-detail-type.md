# Τύπος PaymentMethodDetail

Ο τύπος `PaymentMethodDetail` περιγράφει τον τρόπο πληρωμής του παραστατικού.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                                                | Υποχρεωτικό | Περιγραφή                                         |
|------------------------------------------------------|-------------|---------------------------------------------------|
| [**type**](../appendix/payment-methods)              | **Ναι**     | Τύπος Πληρωμής                                    |
| amount                                               | **Ναι**     | Ποσό Πληρωμής                                     |
| paymentMethodInfo                                    | Όχι         | Πληροφορίες                                       |
| tipAmount                                            | Όχι         | Ποσό φιλοδωρήματος                                |
| transactionId                                        | Όχι         | Μοναδική Ταυτότητα Πληρωμής                       |
| tid                                                  | Όχι         | Κωδικός tid POS                                   |
| [**ProvidersSignature**](./providers-signature-type) | Όχι         | Υπογραφή Πληρωμής Παρόχου                         |
| [**ECRToken**](./ecr-token-type)                     | Όχι         | Υπογραφή Πληρωμής ΦΗΜ με σύστημα λογισμικού (ERP) |

## Παρατηρήσεις

- Το πεδίο amount μπορεί να αντιστοιχεί σε ένα τμήμα της συνολικής αξίας του
  παραστατικού.
- Το πεδίο Πληροφορίες μπορεί να περιέχει επιπλέον πληροφορίες σχετικά με τον
  συγκεκριμένο τύπο (πχ Αρ. Λογαριασμού Τραπέζης).
- Το πεδίο `transactionId` διαβιβάζεται στην περίπτωση πληρωμών με `type = 7`.
- Το πεδίο `ProvidersSignature` διαβιβάζεται στην περίπτωση πληρωμών με `type = 7`
  και όταν η διαβίβαση γίνεται από το **κανάλι του παρόχου**.
- Το πεδίο `ECRToken` διαβιβάζεται στην περίπτωση πληρωμών με `type = 7` και
  όταν η διαβίβαση γίνεται **από ERP**.
- Το πεδίο tid, είναι το tid του POS και διαβιβάζεται υποχρεωτικά στην περίπτωση
  πληρωμών με type = 7 (POS) και έχει διαβιβαστεί είτε το ProvidersSignature είτε το
  ECRToken (ανάλογα την περίπτωση).

## Παραδείγματα

### Πληρωμή με Web Banking

```php
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\ECRToken;
use Firebed\AadeMyData\Enums\PaymentMethod;

$pmd = new PaymentMethodDetail();
$pmd->setType(PaymentMethod::METHOD_6); // Web Banking
$pmd->setAmount(50.5);
$pmd->setPaymentMethodInfo('GR1234567890');
$pmd->setTipAmount(4.5);
```

### Πληρωμή με E-POS

```php
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\ECRToken;
use Firebed\AadeMyData\Enums\PaymentMethod;

$pmd = new PaymentMethodDetail();
$pmd->setType(PaymentMethod::METHOD_7);
$pmd->setTid('example-id');
$pmd->setAmount(50);

// Υπογραφή Πληρωμής ERP
$ecrToken = new ECRToken();
$ecrToken->setSigningAuthor('1234567890');
$ecrToken->setSessionNumber('1234567890');
$pmd->setECRToken($ecrToken);
```
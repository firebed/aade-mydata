# Οδηγός Αναβάθμισης

## Αναβάθμιση από 3.x σε 4.x

Η έκδοση 4.x περιέχει αρκετές αλλαγές, μεταξύ των οποίων αρκετές
προσθήκες επιπλέον δυνατοτήτων.

> [!NOTE]
> Η έκδοση 4.x είναι μια ολική αναδιοργάνωση και οι περισσότερες αλλαγές
> βρίσκονται στο εσωτερικό μέρος του συστήματος. Ωστόσο, ορισμένες αλλαγές
> ενδέχεται να επηρεάσουν τον τρόπο με τον οποίο χρησιμοποιείται το API.

## Ενημέρωση composer

```json
{
  "require": {
    "firebed/aade-mydata": "^4.0"
  }
}
```

## Πιθανές αλλαγές στη χρήση του API

- Μετονομασία `InvoicesDoc`::~~addInvoice()~~ σε `add()`
- Μετονομασία `RequestedDoc`::~~getInvoicesDoc()~~ => `getInvoices()`
- Μετονομασία `RequestedDoc`::~~getCancelledInvoicesDoc()~~ => `getCancelledInvoices()`
- Μετονομασία `RequestedDoc`::~~getIncomeClassificationsDoc()~~ => `getIncomeClassifications()`
- Μετονομασία `RequestedDoc`::~~getExpensesClassificationsDoc()~~ => `getExpensesClassifications()`
- Μετονομασία `RequestedDoc`::~~getPaymentMethodsDoc()~~ => `getPaymentMethods()`
- Μετονομασία της μεθόδου `put` σε `set` για όλα μοντέλα που κληρονομούν την κλάση `Firebed\AadeMyData\Models\Type`
- Τρόπος αντιμετώπισης των εξαιρέσεων (exceptions)

## Τρόπος αντιμετώπισης των εξαιρέσεων

Οι μέθοδοι αλληλεπίδρασης με το σύστημα του myDATA όπως `SendInvoices`, `CancelInvoice` κ.λπ. πλέον
επιστρέφουν εξαιρέσεις τύπου `Firebed\AadeMyData\Exceptions\MyDataException` ή εξαιρέσεις που
κληρονομούν αυτή την κλάση.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;

try {
    $send = new SendInvoices();
    $responses = $send->handle($invoices);
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

## Εσωτερικές αλλαγές

Οι παρακάτω αλλαγές είναι εσωτερικές και δεν επηρεάζουν τη χρήση του API:

### Tests

Ένα πράγμα που με κρατούσε ξύπνιο τα βράδια ήταν το γεγονός ότι οι παλαιές εκδόσεις του API
δεν είχαν καθόλου δοκιμαστική κάλυψη. Όλες οι δοκιμές γινόντουσαν χειροκίνητα χωρίς
επιβεβαίωση σωστής λειτουργίας μετά από κάθε αλλαγή στο κώδικα. Αυτό αλλάζει με την
**έκδοση 4.x**, καθώς πλέον υπάρχει **πλήρης δοκιμαστική κάλυψη** 💥.

### DOMDocument

Στις προηγούμενες εκδόσεις το API χρησιμοποιούσε την κλάση `SimpleXMLElement`
για τη διαχείριση των xml δεδομένων. Αυτό αρχικά δεν ήταν πρόβλημα, αλλά με την αύξηση
των δυνατοτήτων και της πολυπλοκότητας των xml δεδομένων που έφερε το myDATA, η
`SimpleXMLElement` έδειξε τα όριά της, καθώς έβρισκα τον εαυτό μου να εργάζομαι ενάντια σε
αυτήν, αναζητώντας τρόπους διαχείρισης των δεδομένων (ειδικά με τα namespaced tags).

### Σύνοψη παραστατικού

Αυτή ίσως είναι η καλύτερη προσθήκη στο API. Η σύνοψη παραστατικού είναι μια περίπλοκη διαδικασία 
όπου πρέπει να υπολογίσουμε τα ποσά των φόρων, το καθαρό σύνολο, το τελικό σύνολο, τα σύνολα των
χαρακτηρισμών εσόδων και εξόδων κ.λπ. Η δυσκολία επέρχεται όταν υπάρχουν διάφοροι τύποι φόρων και
διάφοροι τύποι χαρακτηρισμών εσόδων/εξόδων, καθώς αυτά θα πρέπει να ομαδοποιούνται κατάλληλα έτσι
ώστε να υπολογίσουμε σωστά τα απαραίτητα σύνολα. Αυτό και μόνο είναι __πάνω από 200 γραμμές__ κώδικα.

Το χειρότερο (στη δικιά μου περίπτωση τουλάχιστον) είναι ότι έπρεπε να επαναλαμβάνω το ίδιο κομμάτι 
κώδικα σε κάθε πρότζεκτ που χρησιμοποιούσε αυτό το API. Αυτό μου γινόταν εφιάλτης, καθώς μετά από
κάθε καινούργια έκδοση που έβγαζε το myDATA, έπρεπε να ελέγχω όλα μου τα πρότζεκτ συνεχώς 😒.

Στην έκδοση 4.x, η σύνοψη παραστατικού είναι πλέον μια μέθοδος `summarizeInvoice` της κλάσης 
`Firebed\AadeMyData\Models\Invoice`. Λαμβάνει υπόψιν όλες τις παραμέτρους του παραστατικού
και υπολογίζει τα απαραίτητα σύνολα αυτόματα χωρίς να χρειάζεται να κάνετε τίποτα άλλο 😀.

```php
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
// ... set some details

$invoice->summarizeInvoice();
```

### Αυτόματη ταξινόμηση των πεδίων του xml

Αυτή ίσως είναι η 2η αγαπημένη μου προσθήκη. Όλα XML που χρησιμοποιεί το myDATA βασίζονται
σε xsd (xml schema) τα οποία καθορίζουν τη δομή του xml και τη σειρά των πεδίων. Αυτό σημαίνει
ότι τα πεδία του xml πρέπει να είναι ταξινομημένα σύμφωνα με το σχήμα. 

Αυτό ήταν πρόβλημα καθώς παλαιότερα το API έβαζε τα πεδία στο xml με τη σειρά που τα πρόσθετε ο χρήστης 
και αυτό οδηγούσε σε λάθη `XMLSyntaxError` κατά την υποβολή των xml στο myDATA.

**Παράδειγμα λανθασμένης σειράς στην έκδοση &le; 3.x**

```php
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Http\SendInvoices;

$invoice = new Invoice();

// Counterpart must come after Issuer
$invoice->setCounterpart(new Counterpart());

// Issuer must come before Counterpart
$invoice->setIssuer(new Counterpart());

$send = new SendInvoices();
$send->handle($invoice);
```

Σύμφωνα με το xsd ο `issuer` πρέπει να έρχεται πριν το `counterpart`, ενώ παραπάνω τα βάλαμε ανάποδα και αυτό 
είναι το αποτέλεσμα της εκτέλεσης:

```xml
<response>
    <index>1</index>
    <statusCode>XMLSyntaxError</statusCode>
    <errors>
        <error>
            <message>Line:13.Position:6.The element 'invoice' in namespace 'http://www.aade.gr/myDATA/invoice/v1.0' has invalid child element 'issuer' in namespace 'http://www.aade.gr/myDATA/invoice/v1.0'. List of possible elements expected: 'invoiceHeader' in namespace 'http://www.aade.gr/myDATA/invoice/v1.0'.</message>
            <code>101</code>
        </error>
    </errors>
</response>
```

> [!TIP]
> **Από την έκδοση 4.x και μετά η σωστή σειρά των πεδίων παρακολουθείται εσωτερικά, και έτσι άσχετα με τη
> σειρά που δομείτε το παραστατικό σας το τελικό xml θα έχει πάντα τη σωστή ταξινόμηση.**

### Docs docs and docs

*"If I had a dollar for everytime someone asked me "How do I use this?" I'd be a millionaire by now 💰."*

Εντάξει, όχι και millionaire. Λαμβάνω όμως συνέχεια ερωτήσεις για το πως μπορεί κάποιος να χρησιμοποιεί
το API. Αρχικά νόμιζα πως το 1 README.md αρχείο στο GitHub θα ήταν αρκετό, τελικά όμως αποδείχτηκε και
ομολογώ πως δεν ήταν. 

Τώρα αντί για 1 `README.md` αρχείο έχουμε **πάνω από 60 .md αρχεία**, το καθένα αφιερωμένο σε ένα μέρος του API.
Επιπλέον, το subdomain [**docs.invoicemaker.gr**](https://docs.invoicemaker.gr) περιέχει όλα τα αρχεία αυτά απ' ευθείας 
από το GitHub σε πιο οργανωμένη μορφή για εύκολη πλοήγηση 🥳.
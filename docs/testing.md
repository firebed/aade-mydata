# Δοκιμές (testing)

Στα tests της εφαρμογής σας δεν θέλετε κλήσεις προς το myDATA. Το `MyData::fake()` απαντά σε **κάθε** κλήση με
ψεύτικη απάντηση, όποια σύνδεση κι αν χρησιμοποιεί (προεπιλεγμένη, `Connection`, `usingGateway()`), και την
καταγράφει για έλεγχο. Δεν χρειάζονται διαπιστευτήρια.

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\MyData;

protected function tearDown(): void
{
    MyData::stopFaking();
}

public function test_an_order_issues_an_invoice(): void
{
    $fake = MyData::fake();

    $this->app->issueInvoice($order);

    $fake->assertSent(SendInvoices::class, fn (SendInvoices $request) => $request->getInvoicesDoc()->count() === 1);
    $fake->assertSentCount(1);
}
```

## Προεπιλεγμένες απαντήσεις

Χωρίς απάντηση για μια κλήση:

| Κλήση                                                                                           | Απάντηση                                                                                   |
|-------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------|
| `SendInvoices`                                                                                  | Επιτυχία ανά παραστατικό, με mark (από 400000000000001), uid και authenticationCode        |
| `SendIncomeClassification`, `SendExpensesClassification`                                        | Επιτυχία ανά χαρακτηρισμό, με classificationMark                                           |
| `SendPaymentsMethod`                                                                            | Επιτυχία ανά πληρωμή, με paymentMethodMark                                                 |
| `CancelInvoice`, `CancelDeliveryNote`, `CancelReceivingNote`                                    | Επιτυχία με cancellationMark                                                               |
| `RequestDocs`, `RequestTransmittedDocs`, `RequestMyIncome`, `RequestMyExpenses`, `RequestVatInfo`, `RequestE3Info` | Κανένα αποτέλεσμα                                                       |
| `RequestProviderDocs`, `RequestReceiverInfo`                                                    | Κανένα αποτέλεσμα                                                                          |
| `SendUnsignedInvoices`                                                                          | Επιτυχία ανά παραστατικό με uid, χωρίς mark                                                |
| Δελτία αποστολής, δηλώσεις (statements), QR                                                     | `LogicException` μέχρι να δοθεί απάντηση                                                   |

## Απαντήσεις ανά κλήση

```php
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Testing\FakeGateway;
use Firebed\AadeMyData\Testing\RecordedRequest;

MyData::fake([
    // Απόρριψη, όπως την επιστρέφει το myDATA (statusCode ValidationError)
    SendInvoices::class => FakeGateway::rejected(101, 'Invalid VAT number'),

    // Μία απάντηση ανά κλήση, με τη σειρά (π.χ. σελίδες με continuationToken)
    RequestDocs::class => FakeGateway::sequence($page1, $page2),

    // Εξαίρεση: ελέγξτε πώς χειρίζεται η εφαρμογή το rate limit
    RequestVatInfo::class => new RateLimitExceededException(retryAfter: 300),

    // Closure: η απάντηση εξαρτάται από την κλήση
    CancelInvoice::class => fn (CancelInvoice $request, RecordedRequest $sent) => FakeGateway::accepted(),

    // Οποιαδήποτε άλλη κλήση
    '*' => file_get_contents(__DIR__.'/stubs/response.xml'),
]);
```

Μια απάντηση μπορεί να είναι:

- XML (string), όπως θα το έστελνε το myDATA,
- μοντέλο: `ResponseDoc`, `Response` (μέσα σε `ResponseDoc`), `RequestedDoc`, `RequestedVatInfo`, …,
- λίστα από `Response` (μία ανά παραστατικό) ή από `Invoice` (ως `RequestedDoc`),
- εξαίρεση, που πετιέται,
- closure που δέχεται την κλήση και το `RecordedRequest` και επιστρέφει κάτι από τα παραπάνω,
- `FakeGateway::sequence(...)`, με `->whenEmpty($response)` για τις κλήσεις μετά το τέλος της.

Σε ένα `ResponseDoc`, κάθε απάντηση χωρίς `index` παίρνει τη θέση της και κάθε επιτυχημένη χωρίς mark παίρνει το
επόμενο mark. Έτσι, για δύο παραστατικά όπου το δεύτερο απορρίπτεται:

```php
MyData::fake([SendInvoices::class => [FakeGateway::accepted(), FakeGateway::rejected(102, 'Wrong total')]]);
```

## Έλεγχοι

```php
$fake->assertSent(SendInvoices::class);
$fake->assertSent(RequestDocs::class, fn (RequestDocs $request, RecordedRequest $sent) => $sent->query['mark'] === '0');
$fake->assertNotSent(CancelInvoice::class);
$fake->assertSentCount(2);                      // όλες οι κλήσεις
$fake->assertSentCount(1, SendInvoices::class); // μόνο οι SendInvoices
$fake->assertNothingSent();

$fake->recorded(SendInvoices::class)[0]->body;  // το XML που στάλθηκε
```

Το `RecordedRequest` έχει την κλήση (`request`), τη μέθοδο (`method`), τις παραμέτρους (`query`), το XML (`body`,
`document()`) και το πλήθος των εγγράφων που στάλθηκαν (`count()`).

Οι listeners της σύνδεσης (`afterSending()`, `withLogger()`) και η επικύρωση (`withValidation()`) τρέχουν κανονικά
και με το fake, οπότε μπορείτε να τους ελέγξετε κι αυτούς.

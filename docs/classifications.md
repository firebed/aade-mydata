# Classifications

myDATA accepts only some combinations of invoice type, classification category and classification type.
`Firebed\AadeMyData\Services\Classifications` gives you the allowed combinations, with labels, and checks
a combination before you send it.

## Allowed combinations

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toArray();
// [
//   'category1_1' => ['E3_561_001', 'E3_561_002', 'E3_561_007'],
//   'category1_2' => ['E3_561_001', 'E3_561_002', 'E3_561_007'],
//   ...
//   'category1_95' => ['E3_596', 'E3_597', null],
// ]
```

A `null` type means the category can be used without a type.

The same, from the enums:

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;

IncomeClassificationType::for(InvoiceType::TYPE_1_1);
InvoiceType::TYPE_1_1->incomeClassifications();

// The types of one category
Classifications::incomeClassifications(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1);
InvoiceType::TYPE_1_1->incomeClassifications(IncomeClassificationCategory::CATEGORY_1_1);
IncomeClassificationCategory::CATEGORY_1_1->for(InvoiceType::TYPE_1_1);
```

Invoice types and categories can also be passed as strings: `Classifications::incomeClassifications('1.1', 'category1_1')`.

Expense classifications work the same way, with `expenseClassifications()`, `ExpenseClassificationType::for()`,
`InvoiceType::expenseClassifications()` and `ExpenseClassificationCategory::for()`.

```php
Classifications::expenseClassifications(InvoiceType::TYPE_1_1, 'category2_1')->toArray();
// ['E3_102_001', 'E3_102_003', 'E3_102_006', 'E3_313_001', 'E3_313_005']
```

## With labels

The labels are the official AADE descriptions, in Greek.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

// Categories
Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabel();
// [
//   'category1_1' => 'Έσοδα από Πώληση Εμπορευμάτων',
//   'category1_2' => 'Έσοδα από Πώληση Προϊόντων',
//   'category1_3' => 'Έσοδα από Παροχή Υπηρεσιών',
//   'category1_4' => 'Έσοδα από Πώληση Παγίων',
//   'category1_5' => 'Λοιπά Έσοδα / Κέρδη',
//   'category1_7' => 'Έσοδα για λογαριασμό τρίτων',
//   'category1_8' => 'Έσοδα προηγούμενων χρήσεων',
//   'category1_9' => 'Έσοδα επομένων χρήσεων',
//   'category1_95' => 'Λοιπά Πληροφοριακά Στοιχεία Εσόδων',
// ]

// Categories with their types
Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabels();
// [
//   'category1_1' => [
//     'E3_561_001' => 'Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών',
//     'E3_561_002' => 'Πωλήσεις αγαθών και υπηρεσιών Χονδρικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000)',
//     'E3_561_007' => 'Πωλήσεις αγαθών και υπηρεσιών Λοιπά',
//   ],
//   ...
// ]

// Types of one category
Classifications::incomeClassifications(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1)->toKeyLabel();
// [
//   'E3_561_001' => 'Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών',
//   'E3_561_002' => 'Πωλήσεις αγαθών και υπηρεσιών Χονδρικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000)',
//   'E3_561_007' => 'Πωλήσεις αγαθών και υπηρεσιών Λοιπά',
// ]
```

Useful for a select box: categories first, then the types of the chosen category.

## Check a combination

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

Classifications::incomeClassificationExists('1.1', 'category1_1', 'E3_561_001'); // true
Classifications::incomeClassificationExists(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1, IncomeClassificationType::E3_561_001); // true

// Category without a type
Classifications::incomeClassificationExists('1.1', 'category1_95'); // true

Classifications::expenseClassificationExists('1.1', 'category2_1', 'E3_102_001'); // true
```

For example, check every invoice line before you send:

```php
foreach ($invoice->getInvoiceDetails() as $row) {
    foreach ($row->getIncomeClassification() ?? [] as $icls) {
        $valid = Classifications::incomeClassificationExists(
            $invoice->getInvoiceHeader()->getInvoiceType(),
            $icls->getClassificationCategory(),
            $icls->getClassificationType(),
        );
    }
}
```

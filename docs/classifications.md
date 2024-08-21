# Χαρακτηρισμοί / Classifications

## Συνδυασμοί χαρακτηρισμών

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1));
// array:9 [
//  "category1_1" => array:3 [
//    0 => "E3_561_001"
//    1 => "E3_561_002"
//    2 => "E3_561_007"
//  ]
//  "category1_2" => array:3 [
//    0 => "E3_561_001"
//    1 => "E3_561_002"
//    2 => "E3_561_007"
//  ]
// ...
// ]

// Alternative 2
IncomeClassificationType::for(InvoiceType::TYPE_1_1);

// Alternative 3
InvoiceType::TYPE_1_1->incomeClassifications();
```

## Συνδυασμοί χαρακτηρισμών με ετικέτες

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabel());
//array:9 [
//  "category1_1" => "Έσοδα από Πώληση Εμπορευμάτων"
//  "category1_2" => "Έσοδα από Πώληση Προϊόντων"
//  "category1_3" => "Έσοδα από Παροχή Υπηρεσιών"
//  "category1_4" => "Έσοδα από Πώληση Παγίων"
//  "category1_5" => "Λοιπά Έσοδα / Κέρδη"
//  "category1_7" => "Έσοδα για λογαριασμό τρίτων"
//  "category1_8" => "Έσοδα προηγούμενων χρήσεων"
//  "category1_9" => "Έσοδα επομένων χρήσεων"
//  "category1_95" => "Λοιπά Πληροφοριακά Στοιχεία Εσόδων"
//]

dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabels())
//array:9 [
//  "category1_1" => array:3 [
//    "E3_561_001" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών"
//    "E3_561_002" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000)"
//    "E3_561_007" => "Πωλήσεις αγαθών και υπηρεσιών Λοιπά"
//  ]
//  "category1_2" => array:3 [
//    "E3_561_001" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών"
//    "E3_561_002" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000)"
//    "E3_561_007" => "Πωλήσεις αγαθών και υπηρεσιών Λοιπά"
//  ]
//  "category1_3" => array:4 [
//    "E3_561_001" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών"
//    "E3_561_002" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000)"
//    "E3_561_007" => "Πωλήσεις αγαθών και υπηρεσιών Λοιπά"
//    "E3_563" => "Πιστωτικοί τόκοι και συναφή έσοδα"
//  ]
//  ...
// ]

dump(Classifications::incomeClassifications(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1)->toKeyLabel())
//array:3 [
//  "E3_561_001" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών"
//  "E3_561_002" => "Πωλήσεις αγαθών και υπηρεσιών Χονδρικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000)"
//  "E3_561_007" => "Πωλήσεις αγαθών και υπηρεσιών Λοιπά"
//]
```

## Επαλήθευση συνδυασμού χαρακτηρισμών

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Services\Classifications;

Classifications::incomeClassificationExists('1.1', 'category1_1', 'E3_561_001');
// or
Classifications::incomeClassificationExists(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1, IncomeClassificationType::E3_561_001);
// Outputs: true

// Same for expense classifications
Classifications::expenseClassificationExists('1.1', 'category2_1', 'E3_102_001');
````
# Income classifications

Income classification of an invoice row. Enums: `IncomeClassificationCategory` and `IncomeClassificationType`.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())->addIncomeClassification(
    IncomeClassificationType::E3_561_001,
    IncomeClassificationCategory::CATEGORY_1_1,
    100.0,
);

// Valid category/type combinations for an invoice type
InvoiceType::TYPE_1_1->incomeClassifications();
```

See [Classifications](../classifications) for the combination helpers.

## Categories

| Code | Enum | `label()` | English |
|---|---|---|---|
| category1_1 | `IncomeClassificationCategory::CATEGORY_1_1` | Έσοδα από Πώληση Εμπορευμάτων | Income from sale of goods |
| category1_2 | `IncomeClassificationCategory::CATEGORY_1_2` | Έσοδα από Πώληση Προϊόντων | Income from sale of products |
| category1_3 | `IncomeClassificationCategory::CATEGORY_1_3` | Έσοδα από Παροχή Υπηρεσιών | Income from provision of services |
| category1_4 | `IncomeClassificationCategory::CATEGORY_1_4` | Έσοδα από Πώληση Παγίων | Income from sale of fixed assets |
| category1_5 | `IncomeClassificationCategory::CATEGORY_1_5` | Λοιπά Έσοδα / Κέρδη | Other income / gains |
| category1_6 | `IncomeClassificationCategory::CATEGORY_1_6` | Αυτοπαραδόσεις / Ιδιοχρησιμοποιήσεις | Self-deliveries / own use |
| category1_7 | `IncomeClassificationCategory::CATEGORY_1_7` | Έσοδα για λογαριασμό τρίτων | Income on behalf of third parties |
| category1_8 | `IncomeClassificationCategory::CATEGORY_1_8` | Έσοδα προηγούμενων χρήσεων | Income of prior years |
| category1_9 | `IncomeClassificationCategory::CATEGORY_1_9` | Έσοδα επομένων χρήσεων | Income of future years |
| category1_10 | `IncomeClassificationCategory::CATEGORY_1_10` | Λοιπές Εγγραφές Τακτοποίησης Εσόδων | Other income adjustment entries |
| category1_95 | `IncomeClassificationCategory::CATEGORY_1_95` | Λοιπά Πληροφοριακά Στοιχεία Εσόδων | Other income information |
| category3 | `IncomeClassificationCategory::CATEGORY_3` | Διακίνηση | Goods movement |

## Types

| Code | Enum | `label()` | English |
|---|---|---|---|
| E3_106 | `IncomeClassificationType::E3_106` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων/Εμπορεύματα | Own production of fixed assets - self-deliveries - inventory destruction / goods |
| E3_205 | `IncomeClassificationType::E3_205` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων/Πρώτες ύλες και λοιπά υλικά | Own production of fixed assets - self-deliveries - inventory destruction / raw and other materials |
| E3_210 | `IncomeClassificationType::E3_210` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων/Προϊόντα και παραγωγή σε εξέλιξη | Own production of fixed assets - self-deliveries - inventory destruction / products and work in progress |
| E3_305 | `IncomeClassificationType::E3_305` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις – Καταστροφές αποθεμάτων/Πρώτες ύλες και λοιπά υλικά | Own production of fixed assets - self-deliveries - inventory destruction / raw and other materials |
| E3_310 | `IncomeClassificationType::E3_310` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων/Προϊόντα και παραγωγή σε εξέλιξη | Own production of fixed assets - self-deliveries - inventory destruction / products and work in progress |
| E3_318 | `IncomeClassificationType::E3_318` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων/Έξοδα παραγωγής | Own production of fixed assets - self-deliveries - inventory destruction / production costs |
| E3_561_001 | `IncomeClassificationType::E3_561_001` | Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών | Sales of goods and services, wholesale - professionals |
| E3_561_002 | `IncomeClassificationType::E3_561_002` | Πωλήσεις αγαθών και υπηρεσιών Χονδρικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000) | Sales of goods and services, wholesale under art. 39a par. 5 of the VAT Code (L. 2859/2000) |
| E3_561_003 | `IncomeClassificationType::E3_561_003` | Πωλήσεις αγαθών και υπηρεσιών Λιανικές - Ιδιωτική Πελατεία | Sales of goods and services, retail - private customers |
| E3_561_004 | `IncomeClassificationType::E3_561_004` | Πωλήσεις αγαθών και υπηρεσιών Λιανικές βάσει άρθρου 39α παρ 5 του Κώδικα Φ.Π.Α. (Ν.2859/2000) | Sales of goods and services, retail under art. 39a par. 5 of the VAT Code (L. 2859/2000) |
| E3_561_005 | `IncomeClassificationType::E3_561_005` | Πωλήσεις αγαθών και υπηρεσιών Εξωτερικού Ενδοκοινοτικές | Sales of goods and services abroad, intra-community |
| E3_561_006 | `IncomeClassificationType::E3_561_006` | Πωλήσεις αγαθών και υπηρεσιών Εξωτερικού Τρίτες Χώρες | Sales of goods and services abroad, third countries |
| E3_561_007 | `IncomeClassificationType::E3_561_007` | Πωλήσεις αγαθών και υπηρεσιών Λοιπά | Sales of goods and services, other |
| E3_562 | `IncomeClassificationType::E3_562` | Λοιπά συνήθη έσοδα | Other ordinary income |
| E3_563 | `IncomeClassificationType::E3_563` | Πιστωτικοί τόκοι και συναφή έσοδα | Interest income and related income |
| E3_564 | `IncomeClassificationType::E3_564` | Πιστωτικές συναλλαγματικές διαφορές | Foreign exchange gains |
| E3_565 | `IncomeClassificationType::E3_565` | Έσοδα συμμετοχών | Income from participations |
| E3_566 | `IncomeClassificationType::E3_566` | Κέρδη από διάθεση μη κυκλοφορούντων περιουσιακών στοιχείων | Gains on disposal of non-current assets |
| E3_567 | `IncomeClassificationType::E3_567` | Κέρδη από αναστροφή προβλέψεων και απομειώσεων | Gains from reversal of provisions and impairments |
| E3_568 | `IncomeClassificationType::E3_568` | Κέρδη από επιμέτρηση στην εύλογη αξία | Gains from fair value measurement |
| E3_570 | `IncomeClassificationType::E3_570` | Ασυνήθη έσοδα και κέρδη | Unusual income and gains |
| E3_595 | `IncomeClassificationType::E3_595` | Έξοδα σε ιδιοπαραγωγή | Own production expenses |
| E3_596 | `IncomeClassificationType::E3_596` | Επιδοτήσεις - Επιχορηγήσεις | Subsidies - grants |
| E3_597 | `IncomeClassificationType::E3_597` | Επιδοτήσεις - Επιχορηγήσεις για επενδυτικούς σκοπούς - κάλυψη δαπανών | Subsidies - grants for investment purposes - cost coverage |
| E3_880_001 | `IncomeClassificationType::E3_880_001` | Πωλήσεις Παγίων Χονδρικές | Sales of fixed assets, wholesale |
| E3_880_002 | `IncomeClassificationType::E3_880_002` | Πωλήσεις Παγίων Λιανικές | Sales of fixed assets, retail |
| E3_880_003 | `IncomeClassificationType::E3_880_003` | Πωλήσεις Παγίων Εξωτερικού Ενδοκοινοτικές | Sales of fixed assets abroad, intra-community |
| E3_880_004 | `IncomeClassificationType::E3_880_004` | Πωλήσεις Παγίων Εξωτερικού Τρίτες Χώρες | Sales of fixed assets abroad, third countries |
| E3_881_001 | `IncomeClassificationType::E3_881_001` | Πωλήσεις για λογαριασμό Τρίτων Χονδρικές | Sales on behalf of third parties, wholesale |
| E3_881_002 | `IncomeClassificationType::E3_881_002` | Πωλήσεις για λογαριασμό Τρίτων Λιανικές | Sales on behalf of third parties, retail |
| E3_881_003 | `IncomeClassificationType::E3_881_003` | Πωλήσεις για λογαριασμό Τρίτων Εξωτερικού Ενδοκοινοτικές | Sales on behalf of third parties abroad, intra-community |
| E3_881_004 | `IncomeClassificationType::E3_881_004` | Πωλήσεις για λογαριασμό Τρίτων Εξωτερικού Τρίτες Χώρες | Sales on behalf of third parties abroad, third countries |
| E3_598_001 | `IncomeClassificationType::E3_598_001` | Πωλήσεις αγαθών που υπάγονται σε ΕΦΚ | Sales of goods subject to excise duty (EFK) |
| E3_598_003 | `IncomeClassificationType::E3_598_003` | Πωλήσεις για λογαριασμό αγροτών μέσω αγροτικού συνεταιρισμού κ.λ.π | Sales on behalf of farmers through an agricultural cooperative etc. |

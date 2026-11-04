# Expense classifications

Expense classification of an invoice row. Enums: `ExpenseClassificationCategory` and `ExpenseClassificationType`.

```php
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())->addExpensesClassification(
    ExpenseClassificationType::E3_102_001,
    ExpenseClassificationCategory::CATEGORY_2_1,
    100.0,
);

// Valid category/type combinations for an invoice type
InvoiceType::TYPE_1_1->expenseClassifications();

// VAT_361 ... VAT_366 and NOT_VAT_295 are VAT classifications
ExpenseClassificationType::VAT_361->isVatClassification(); // true
```

See [Classifications](../classifications) for the combination helpers.

## Categories

| Code | Enum | `label()` | English |
|---|---|---|---|
| category2_1 | `ExpenseClassificationCategory::CATEGORY_2_1` | Αγορές Εμπορευμάτων (-) / (+) | Purchases of goods (-) / (+) |
| category2_2 | `ExpenseClassificationCategory::CATEGORY_2_2` | Αγορές Α'-Β' Υλών (-) / (+) | Purchases of raw and auxiliary materials (-) / (+) |
| category2_3 | `ExpenseClassificationCategory::CATEGORY_2_3` | Λήψη Υπηρεσιών (-) / (+) | Receipt of services (-) / (+) |
| category2_4 | `ExpenseClassificationCategory::CATEGORY_2_4` | Γενικά Έξοδα με δικαίωμα έκπτωσης ΦΠΑ (-) / (+) | General expenses with VAT deduction right (-) / (+) |
| category2_5 | `ExpenseClassificationCategory::CATEGORY_2_5` | Γενικά Έξοδα χωρίς δικαίωμα έκπτωσης ΦΠΑ (-) / (+) | General expenses without VAT deduction right (-) / (+) |
| category2_6 | `ExpenseClassificationCategory::CATEGORY_2_6` | Αμοιβές και Παροχές προσωπικού (-) / (+) | Staff pay and benefits (-) / (+) |
| category2_7 | `ExpenseClassificationCategory::CATEGORY_2_7` | Αγορές Παγίων (-) / (+) | Purchases of fixed assets (-) / (+) |
| category2_8 | `ExpenseClassificationCategory::CATEGORY_2_8` | Αποσβέσεις Παγίων (-) / (+) | Depreciation of fixed assets (-) / (+) |
| category2_9 | `ExpenseClassificationCategory::CATEGORY_2_9` | Έξοδα για λ/σμο τρίτων (-) / (+) | Expenses on behalf of third parties (-) / (+) |
| category2_10 | `ExpenseClassificationCategory::CATEGORY_2_10` | Έξοδα προηγούμενων χρήσεων (-) / (+) | Expenses of prior years (-) / (+) |
| category2_11 | `ExpenseClassificationCategory::CATEGORY_2_11` | Έξοδα επομένων χρήσεων (-) / (+) | Expenses of future years (-) / (+) |
| category2_12 | `ExpenseClassificationCategory::CATEGORY_2_12` | Λοιπές Εγγραφές Τακτοποίησης Εξόδων (-) / (+) | Other expense adjustment entries (-) / (+) |
| category2_13 | `ExpenseClassificationCategory::CATEGORY_2_13` | Αποθέματα Έναρξης Περιόδου (-) / (+) | Opening inventory (-) / (+) |
| category2_14 | `ExpenseClassificationCategory::CATEGORY_2_14` | Αποθέματα Λήξης Περιόδου (-) / (+) | Closing inventory (-) / (+) |
| category2_95 | `ExpenseClassificationCategory::CATEGORY_2_95` | Λοιπά Πληροφοριακά Στοιχεία Εξόδων (-) / (+) | Other expense information (-) / (+) |

## Types

| Code | Enum | `label()` | English |
|---|---|---|---|
| E3_101 | `ExpenseClassificationType::E3_101` | Εμπορεύματα έναρξης | Opening goods inventory |
| E3_102_001 | `ExpenseClassificationType::E3_102_001` | Αγορές εμπορευμάτων χρήσης (καθαρό ποσό)/Χονδρικές | Purchases of goods for the year (net amount) / wholesale |
| E3_102_002 | `ExpenseClassificationType::E3_102_002` | Αγορές εμπορευμάτων χρήσης (καθαρό ποσό)/Λιανικές | Purchases of goods for the year (net amount) / retail |
| E3_102_003 | `ExpenseClassificationType::E3_102_003` | Αγορές εμπορευμάτων χρήσης (καθαρό ποσό)/Αγαθών του άρθρου 39α παρ.5 του Κώδικα Φ.Π.Α. (ν.2859/2000) | Purchases of goods for the year (net amount) / goods of art. 39a par. 5 of the VAT Code (L. 2859/2000) |
| E3_102_004 | `ExpenseClassificationType::E3_102_004` | Αγορές εμπορευμάτων χρήσης (καθαρό ποσό)/Εξωτερικού Ενδοκοινοτικές | Purchases of goods for the year (net amount) / abroad, intra-community |
| E3_102_005 | `ExpenseClassificationType::E3_102_005` | Αγορές εμπορευμάτων χρήσης (καθαρό ποσό)/Εξωτερικού Τρίτες Χώρες | Purchases of goods for the year (net amount) / abroad, third countries |
| E3_102_006 | `ExpenseClassificationType::E3_102_006` | Αγορές εμπορευμάτων χρήσης (καθαρό ποσό)Λοιπά | Purchases of goods for the year (net amount) / other |
| E3_104 | `ExpenseClassificationType::E3_104` | Εμπορεύματα λήξης | Closing goods inventory |
| E3_201 | `ExpenseClassificationType::E3_201` | Πρώτες ύλες και υλικά έναρξης/Παραγωγή | Opening raw materials and supplies / production |
| E3_202_001 | `ExpenseClassificationType::E3_202_001` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Χονδρικές | Purchases of raw materials and supplies for the year (net amount) / wholesale |
| E3_202_002 | `ExpenseClassificationType::E3_202_002` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Λιανικές | Purchases of raw materials and supplies for the year (net amount) / retail |
| E3_202_003 | `ExpenseClassificationType::E3_202_003` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Εξωτερικού Ενδοκοινοτικές | Purchases of raw materials and supplies for the year (net amount) / abroad, intra-community |
| E3_202_004 | `ExpenseClassificationType::E3_202_004` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Εξωτερικού Τρίτες Χώρες | Purchases of raw materials and supplies for the year (net amount) / abroad, third countries |
| E3_202_005 | `ExpenseClassificationType::E3_202_005` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Λοιπά | Purchases of raw materials and supplies for the year (net amount) / other |
| E3_204 | `ExpenseClassificationType::E3_204` | Αποθέματα λήξης πρώτων υλών και υλικών/Παραγωγή | Closing raw materials and supplies / production |
| E3_207 | `ExpenseClassificationType::E3_207` | Προϊόντα και παραγωγή σε εξέλιξη έναρξης/Παραγωγή | Opening products and work in progress / production |
| E3_209 | `ExpenseClassificationType::E3_209` | Προϊόντα και παραγωγή σε εξέλιξη λήξης/Παραγωγή | Closing products and work in progress / production |
| E3_301 | `ExpenseClassificationType::E3_301` | Πρώτες ύλες και υλικά έναρξης/Αγροτική | Opening raw materials and supplies / agriculture |
| E3_302_001 | `ExpenseClassificationType::E3_302_001` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Χονδρικές | Purchases of raw materials and supplies for the year (net amount) / wholesale |
| E3_302_002 | `ExpenseClassificationType::E3_302_002` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Λιανικές | Purchases of raw materials and supplies for the year (net amount) / retail |
| E3_302_003 | `ExpenseClassificationType::E3_302_003` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Εξωτερικού Ενδοκοινοτικές | Purchases of raw materials and supplies for the year (net amount) / abroad, intra-community |
| E3_302_004 | `ExpenseClassificationType::E3_302_004` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Εξωτερικού Τρίτες Χώρες | Purchases of raw materials and supplies for the year (net amount) / abroad, third countries |
| E3_302_005 | `ExpenseClassificationType::E3_302_005` | Αγορές πρώτων υλών και υλικών χρήσης (καθαρό ποσό)/Λοιπά | Purchases of raw materials and supplies for the year (net amount) / other |
| E3_304 | `ExpenseClassificationType::E3_304` | Αποθέματα λήξης πρώτων υλών και υλικών/Αγροτική | Closing raw materials and supplies / agriculture |
| E3_307 | `ExpenseClassificationType::E3_307` | Προϊόντα και παραγωγή σε εξέλιξη έναρξης/Αγροτική | Opening products and work in progress / agriculture |
| E3_309 | `ExpenseClassificationType::E3_309` | Προϊόντα και παραγωγή σε εξέλιξη λήξης/Αγροτική | Closing products and work in progress / agriculture |
| E3_312 | `ExpenseClassificationType::E3_312` | Αποθέματα έναρξης (ζώων - φυτών) | Opening inventory (animals - plants) |
| E3_313_001 | `ExpenseClassificationType::E3_313_001` | Αγορές ζώων - φυτών (καθαρό ποσό)/Χονδρικές | Purchases of animals - plants (net amount) / wholesale |
| E3_313_002 | `ExpenseClassificationType::E3_313_002` | Αγορές ζώων - φυτών (καθαρό ποσό)/Λιανικές | Purchases of animals - plants (net amount) / retail |
| E3_313_003 | `ExpenseClassificationType::E3_313_003` | Αγορές ζώων - φυτών (καθαρό ποσό)/Εξωτερικού Ενδοκοινοτικές | Purchases of animals - plants (net amount) / abroad, intra-community |
| E3_313_004 | `ExpenseClassificationType::E3_313_004` | Αγορές ζώων - φυτών (καθαρό ποσό)/Εξωτερικού Τρίτες Χώρες | Purchases of animals - plants (net amount) / abroad, third countries |
| E3_313_005 | `ExpenseClassificationType::E3_313_005` | Αγορές ζώων - φυτών (καθαρό ποσό)/Λοιπά | Purchases of animals - plants (net amount) / other |
| E3_315 | `ExpenseClassificationType::E3_315` | Αποθέματα τέλους (ζώων - φυτών)/Αγροτική | Closing inventory (animals - plants) / agriculture |
| E3_581_001 | `ExpenseClassificationType::E3_581_001` | Παροχές σε εργαζόμενους/Μικτές αποδοχές | Employee benefits / gross pay |
| E3_581_002 | `ExpenseClassificationType::E3_581_002` | Παροχές σε εργαζόμενους/Εργοδοτικές εισφορές | Employee benefits / employer contributions |
| E3_581_003 | `ExpenseClassificationType::E3_581_003` | Παροχές σε εργαζόμενους/Λοιπές παροχές | Employee benefits / other benefits |
| E3_582 | `ExpenseClassificationType::E3_582` | Ζημιές επιμέτρησης περιουσιακών στοιχείων | Losses from asset measurement |
| E3_583 | `ExpenseClassificationType::E3_583` | Χρεωστικές συναλλαγματικές διαφορές | Foreign exchange losses |
| E3_584 | `ExpenseClassificationType::E3_584` | Ζημιές από διάθεση-απόσυρση μη κυκλοφορούντων περιουσιακών στοιχείων | Losses on disposal or retirement of non-current assets |
| E3_585_001 | `ExpenseClassificationType::E3_585_001` | Προμήθειες διαχείρισης ημεδαπής - αλλοδαπής (management fees) | Domestic - foreign management fees |
| E3_585_002 | `ExpenseClassificationType::E3_585_002` | Δαπάνες από συνδεδεμένες επιχειρήσεις | Expenses from related companies |
| E3_585_003 | `ExpenseClassificationType::E3_585_003` | Δαπάνες από μη συνεργαζόμενα κράτη ή από κράτη με προνομιακό φορολογικό καθεστώς | Expenses from non-cooperative states or states with a preferential tax regime |
| E3_585_004 | `ExpenseClassificationType::E3_585_004` | Δαπάνες για ενημερωτικές ημερίδες | Expenses for information events |
| E3_585_005 | `ExpenseClassificationType::E3_585_005` | Έξοδα υποδοχής και φιλοξενίας | Reception and hospitality expenses |
| E3_585_006 | `ExpenseClassificationType::E3_585_006` | Έξοδα ταξιδιών | Travel expenses |
| E3_585_007 | `ExpenseClassificationType::E3_585_007` | Ασφαλιστικές Εισφορές Αυτοαπασχολούμενων | Social security contributions of the self-employed |
| E3_585_008 | `ExpenseClassificationType::E3_585_008` | Έξοδα και προμήθειες παραγγελιοδόχου για λογαριασμό αγροτών | Agent expenses and commissions on behalf of farmers |
| E3_585_009 | `ExpenseClassificationType::E3_585_009` | Λοιπές Αμοιβές για υπηρεσίες ημεδαπής | Other fees for domestic services |
| E3_585_010 | `ExpenseClassificationType::E3_585_010` | Λοιπές Αμοιβές για υπηρεσίες αλλοδαπής | Other fees for foreign services |
| E3_585_011 | `ExpenseClassificationType::E3_585_011` | Ενέργεια | Energy |
| E3_585_012 | `ExpenseClassificationType::E3_585_012` | Ύδρευση | Water supply |
| E3_585_013 | `ExpenseClassificationType::E3_585_013` | Τηλεπικοινωνίες | Telecommunications |
| E3_585_014 | `ExpenseClassificationType::E3_585_014` | Ενοίκια | Rents |
| E3_585_015 | `ExpenseClassificationType::E3_585_015` | Διαφήμιση και προβολή | Advertising and promotion |
| E3_585_016 | `ExpenseClassificationType::E3_585_016` | Λοιπά έξοδα | Other expenses |
| E3_585_017 | `ExpenseClassificationType::E3_585_017` | Διάφορα λειτουργικά έξοδα Ζ2 | Various operating expenses Z2 |
| E3_586 | `ExpenseClassificationType::E3_586` | Χρεωστικοί τόκοι και συναφή έξοδα | Interest expense and related costs |
| E3_587 | `ExpenseClassificationType::E3_587` | Αποσβέσεις | Depreciation |
| E3_588 | `ExpenseClassificationType::E3_588` | Ασυνήθη έξοδα, ζημιές και πρόστιμα | Unusual expenses, losses and fines |
| E3_589 | `ExpenseClassificationType::E3_589` | Προβλέψεις (εκτός από προβλέψεις για το προσωπικό) | Provisions (except staff provisions) |
| E3_881_001 | `ExpenseClassificationType::E3_881_001` | Πωλήσεις για λογαριασμό Τρίτων Χονδρικές | Sales on behalf of third parties, wholesale |
| E3_881_002 | `ExpenseClassificationType::E3_881_002` | Πωλήσεις για λογαριασμό Τρίτων Λιανικές | Sales on behalf of third parties, retail |
| E3_881_003 | `ExpenseClassificationType::E3_881_003` | Πωλήσεις για λογαριασμό Τρίτων Εξωτερικού Ενδοκοινοτικές | Sales on behalf of third parties abroad, intra-community |
| E3_881_004 | `ExpenseClassificationType::E3_881_004` | Πωλήσεις για λογαριασμό Τρίτων Εξωτερικού Τρίτες Χώρες | Sales on behalf of third parties abroad, third countries |
| E3_882_001 | `ExpenseClassificationType::E3_882_001` | Αγορές ενσώματων παγίων χρήσης/Χονδρικές | Purchases of tangible fixed assets for the year / wholesale |
| E3_882_002 | `ExpenseClassificationType::E3_882_002` | Αγορές ενσώματων παγίων χρήσης/Λιανικές | Purchases of tangible fixed assets for the year / retail |
| E3_882_003 | `ExpenseClassificationType::E3_882_003` | Αγορές ενσώματων παγίων χρήσης/Εξωτερικού Ενδοκοινοτικές | Purchases of tangible fixed assets for the year / abroad, intra-community |
| E3_882_004 | `ExpenseClassificationType::E3_882_004` | Αγορές ενσώματων παγίων χρήσης/Εξωτερικού Τρίτες Χώρες | Purchases of tangible fixed assets for the year / abroad, third countries |
| E3_883_001 | `ExpenseClassificationType::E3_883_001` | Αγορές μη ενσώματων παγίων χρήσης/Χονδρικές | Purchases of intangible fixed assets for the year / wholesale |
| E3_883_002 | `ExpenseClassificationType::E3_883_002` | Αγορές μη ενσώματων παγίων χρήσης/Λιανικές | Purchases of intangible fixed assets for the year / retail |
| E3_883_003 | `ExpenseClassificationType::E3_883_003` | Αγορές μη ενσώματων παγίων χρήσης/Εξωτερικού Ενδοκοινοτικές | Purchases of intangible fixed assets for the year / abroad, intra-community |
| E3_883_004 | `ExpenseClassificationType::E3_883_004` | Αγορές μη ενσώματων παγίων χρήσης/Εξωτερικού Τρίτες Χώρες | Purchases of intangible fixed assets for the year / abroad, third countries |
| VAT_361 | `ExpenseClassificationType::VAT_361` | Αγορές & δαπάνες στο εσωτερικό της χώρας | Domestic purchases and expenses |
| VAT_362 | `ExpenseClassificationType::VAT_362` | Αγορές & εισαγωγές επενδ. Αγαθών (πάγια) | Purchases and imports of investment goods (fixed assets) |
| VAT_363 | `ExpenseClassificationType::VAT_363` | Λοιπές εισαγωγές εκτός επενδ. Αγαθών (πάγια) | Other imports except investment goods (fixed assets) |
| VAT_364 | `ExpenseClassificationType::VAT_364` | Ενδοκοινοτικές αποκτήσεις αγαθών | Intra-community acquisitions of goods |
| VAT_365 | `ExpenseClassificationType::VAT_365` | Ενδοκοινοτικές λήψεις υπηρεσιών άρθρ. 14.2.α | Intra-community receipt of services, art. 14.2.a |
| VAT_366 | `ExpenseClassificationType::VAT_366` | Λοιπές πράξεις λήπτη | Other recipient transactions |
| E3_103 | `ExpenseClassificationType::E3_103` | Απομείωση εμπορευμάτων | Impairment of goods |
| E3_203 | `ExpenseClassificationType::E3_203` | Απομείωση πρώτων υλών και υλικών | Impairment of raw materials and supplies |
| E3_303 | `ExpenseClassificationType::E3_303` | Απομείωση πρώτων υλών και υλικών | Impairment of raw materials and supplies |
| E3_208 | `ExpenseClassificationType::E3_208` | Απομείωση προϊόντων και παραγωγής σε εξέλιξη | Impairment of products and work in progress |
| E3_308 | `ExpenseClassificationType::E3_308` | Απομείωση προϊόντων και παραγωγής σε εξέλιξη | Impairment of products and work in progress |
| E3_314 | `ExpenseClassificationType::E3_314` | Απομείωση ζώων - φυτών – εμπορευμάτων | Impairment of animals - plants - goods |
| E3_106 | `ExpenseClassificationType::E3_106` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων | Own production of fixed assets - self-deliveries - inventory destruction |
| E3_205 | `ExpenseClassificationType::E3_205` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων | Own production of fixed assets - self-deliveries - inventory destruction |
| E3_305 | `ExpenseClassificationType::E3_305` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων | Own production of fixed assets - self-deliveries - inventory destruction |
| E3_210 | `ExpenseClassificationType::E3_210` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων | Own production of fixed assets - self-deliveries - inventory destruction |
| E3_310 | `ExpenseClassificationType::E3_310` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων | Own production of fixed assets - self-deliveries - inventory destruction |
| E3_318 | `ExpenseClassificationType::E3_318` | Ιδιοπαραγωγή παγίων - Αυτοπαραδόσεις - Καταστροφές αποθεμάτων | Own production of fixed assets - self-deliveries - inventory destruction |
| E3_598_002 | `ExpenseClassificationType::E3_598_002` | Αγορές αγαθών που υπάγονται σε ΕΦΚ | Purchases of goods subject to excise duty (EFK) |
| NOT_VAT_295 | `ExpenseClassificationType::NOT_VAT_295` | Μη συμμετοχή στο ΦΠΑ (έξοδα – εισροές) | Outside the scope of VAT (expenses - inputs) |

# Using the enums

Every code list in this appendix is a backed enum in `Firebed\AadeMyData\Enums`. The enum value is the code myDATA expects.

```php
use Firebed\AadeMyData\Enums\VatCategory;

VatCategory::RATE_24->value;   // 1
VatCategory::from(1);          // VatCategory::RATE_24
VatCategory::tryFrom(99);      // null
VatCategory::cases();          // all cases

VatCategory::RATE_24->label(); // "ΦΠΑ συντελεστής 24%"
VatCategory::labels();         // [1 => "ΦΠΑ συντελεστής 24%", 2 => "ΦΠΑ συντελεστής 13%", ...]
```

- `label()` returns the official AADE description, in Greek. The English column in the tables is a translation for reference.
- `labels()` returns every label keyed by code. Use it to fill a `<select>`.
- Pass enum cases to the model setters. Getters return the enum case, so you can call `label()` on the result:

```php
$row->setVatCategory(VatCategory::RATE_24);

$row->getVatCategory()?->label(); // "ΦΠΑ συντελεστής 24%"
```

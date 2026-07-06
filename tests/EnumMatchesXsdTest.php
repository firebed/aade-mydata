<?php

namespace Tests;

use Firebed\AadeMyData\Enums\CountryCode;
use Firebed\AadeMyData\Enums\CurrencyCode;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\FuelCode;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;

/**
 * Guards that PHP enums stay in sync with the enumerations declared in the
 * bundled XSD, so the two can never silently drift.
 *
 * NOTE: InvoiceType and ExpenseClassificationType are intentionally NOT covered
 * here yet — they currently diverge from the XSD (see project notes) and need a
 * maintainer decision before they can be guarded.
 */
class EnumMatchesXsdTest extends TestCase
{
    public function test_enums_match_their_xsd_enumerations(): void
    {
        $xsd = file_get_contents(__DIR__ . '/../xsd/SimpleTypes-' . Invoice::VERSION . '.xsd');
        $this->assertNotFalse($xsd);

        $pairs = [
            [FuelCode::class, 'FuelCodes'],
            [CountryCode::class, 'CountryType'],
            [CurrencyCode::class, 'CurrencyType'],
            [DeliveryOutcomeType::class, 'DeliveryOutcomeType'],
            [IncomeClassificationType::class, 'IncomeClassificationValueType'],
            [IncomeClassificationCategory::class, 'IncomeClassificationCategoryType'],
            [ExpenseClassificationCategory::class, 'ExpensesClassificationCategoryType'],
        ];

        $problems = [];

        foreach ($pairs as [$enumClass, $xsdType]) {
            preg_match('#<xs:simpleType name="' . preg_quote($xsdType, '#') . '">(.*?)</xs:simpleType>#s', $xsd, $block);
            preg_match_all('#<xs:enumeration value="([^"]*)"#', $block[1] ?? '', $m);

            $xsdVals = $m[1];
            $enumVals = array_map(fn ($c) => (string) $c->value, $enumClass::cases());
            sort($xsdVals);
            sort($enumVals);

            if ($xsdVals !== $enumVals) {
                $problems[$xsdType] = [
                    'missing_in_enum' => array_values(array_diff($xsdVals, $enumVals)),
                    'extra_in_enum' => array_values(array_diff($enumVals, $xsdVals)),
                ];
            }
        }

        $this->assertSame([], $problems, 'Enum/XSD mismatches: ' . json_encode($problems, JSON_UNESCAPED_UNICODE));
    }

    /**
     * ExpenseClassificationType can't match the XSD exactly: it carries E3_585_017,
     * which is in the AADE PDF + docs but not the XSD. So guard the dangerous
     * direction only — every XSD expense code must exist in the enum (extras allowed),
     * otherwise such codes would be dropped to null when reading invoices.
     */
    public function test_expense_classification_contains_all_xsd_codes(): void
    {
        $xsd = file_get_contents(__DIR__ . '/../xsd/SimpleTypes-' . Invoice::VERSION . '.xsd');
        $this->assertNotFalse($xsd);

        preg_match('#<xs:simpleType name="ExpensesClassificationValueType">(.*?)</xs:simpleType>#s', $xsd, $block);
        preg_match_all('#<xs:enumeration value="([^"]*)"#', $block[1] ?? '', $m);

        $enumVals = array_map(fn ($c) => (string) $c->value, ExpenseClassificationType::cases());
        $missing = array_values(array_diff($m[1], $enumVals));

        $this->assertSame([], $missing, 'ExpenseClassificationType is missing XSD codes: ' . implode(', ', $missing));
    }
}

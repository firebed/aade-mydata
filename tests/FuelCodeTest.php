<?php

namespace Tests;

use Firebed\AadeMyData\Enums\FuelCode;
use PHPUnit\Framework\TestCase;

class FuelCodeTest extends TestCase
{
    // The FuelCode-vs-XSD value match is guarded centrally in EnumMatchesXsdTest.

    public function test_every_case_resolves_a_label(): void
    {
        foreach (FuelCode::cases() as $case) {
            $this->assertNotSame('', $case->label(), "Missing label for fuel code {$case->value}");
        }
    }

    public function test_v2_0_2_codes_have_expected_labels(): void
    {
        // Codes added for v2.0.2 (myDATA section 8.17)
        $this->assertSame('Βενζίνη αεροπλάνων', FuelCode::from(14)->label());
        $this->assertSame('Ειδικό καύσιμο αεριωθουμένων', FuelCode::from(15)->label());
        $this->assertSame('Diesel άλλων χρήσεων', FuelCode::from(33)->label());
        $this->assertSame('Diesel ναυτιλίας', FuelCode::from(34)->label());
        $this->assertSame('Κηροζίνη JP1', FuelCode::from(35)->label());
        $this->assertSame('Κηροζίνη άλλων χρήσεων', FuelCode::from(36)->label());
        $this->assertSame('Μαζούτ', FuelCode::from(37)->label());
        $this->assertSame('Μαζούτ ναυτιλίας', FuelCode::from(38)->label());

        // Labels corrected from the AADE documentation typos
        $this->assertSame('Diesel Heating', FuelCode::from(30)->label());
        $this->assertSame('Diesel Heating premium', FuelCode::from(31)->label());
        $this->assertSame('Diesel Light', FuelCode::from(32)->label());
    }
}

<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ReceivingNotePurpose;
use PHPUnit\Framework\TestCase;

class ReceivingNotePurposeTest extends TestCase
{
    public function test_cases_cover_codes_1_to_7(): void
    {
        $this->assertCount(7, ReceivingNotePurpose::cases());
        $this->assertSame(1, ReceivingNotePurpose::NOT_OBLIGED_TO_ISSUE->value);
        $this->assertSame(7, ReceivingNotePurpose::OTHER_CASES->value);
        $this->assertSame(ReceivingNotePurpose::QUANTITATIVE_CONTROL, ReceivingNotePurpose::from(5));
    }

    public function test_label_returns_greek_description(): void
    {
        $this->assertSame('ΜΗ ΥΠΟΧΡΕΟΣ ΕΚΔΟΣΗΣ', ReceivingNotePurpose::NOT_OBLIGED_TO_ISSUE->label());
        $this->assertSame('ΛΟΙΠΕΣ ΠΕΡΙΠΤΩΣΕΙΣ', ReceivingNotePurpose::OTHER_CASES->label());
    }

    public function test_labels_helper_returns_all_cases(): void
    {
        $this->assertCount(7, ReceivingNotePurpose::labels());
    }
}

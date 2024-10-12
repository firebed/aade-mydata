<?php

namespace Tests;

use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceDetails;
use PHPUnit\Framework\TestCase;
use Firebed\AadeMyData\Models\Type;
use ReflectionClass;

class EnumCacheTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset the static enum cache after each test
        $reflection = new ReflectionClass(Type::class);
        $enumCacheProperty = $reflection->getProperty('enumCache');
        $enumCacheProperty->setValue([]);
    }

    public function test_enum_cache_is_updated_after_check()
    {
        $type = new IncomeClassification();

        // Initially, the cache should be empty
        $this->assertEmpty($this->getEnumCache());

        // Call isEnum to populate the cache
        $this->assertTrue($type->isEnum('classificationType'));

        // After checking, the cache should have the 'classificationType' enum entry
        $enumCache = $this->getEnumCache();
        $this->assertArrayHasKey(IncomeClassificationType::class, $enumCache);
        $this->assertTrue($enumCache[IncomeClassificationType::class]);
    }

    public function test_enum_cache_handles_non_casted_enums_correctly()
    {
        $type = new IncomeClassification();

        // Initially, the cache should be empty
        $this->assertEmpty($this->getEnumCache());

        // Call isEnum with a non-existent cast
        $this->assertFalse($type->isEnum('nonexistent'));

        // The cache should not contain the nonexistent key
        $enumCache = $this->getEnumCache();
        $this->assertArrayNotHasKey('invalid', $enumCache);
    }

    public function test_enum_cache_handles_non_enum_correctly()
    {
        $row = new InvoiceDetails();

        // Initially, the cache should be empty
        $this->assertEmpty($this->getEnumCache());

        // Call isEnum with a non-enum type
        $this->assertFalse($row->isEnum('incomeClassification'));

        // The cache should have false for the nonexistent key, because
        // IncomeClassification although cast, it is a Type and not an enum.
        $enumCache = $this->getEnumCache();
        $this->assertFalse($enumCache[IncomeClassification::class]);
    }

    private function getEnumCache(): array
    {
        $reflection = new ReflectionClass(Type::class);
        $enumCacheProperty = $reflection->getProperty('enumCache');
        return $enumCacheProperty->getValue();
    }
}
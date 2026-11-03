<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

use ReflectionClassConstant;

/**
 * Labels for backed enums whose cases carry a #[Label] attribute. The attributes
 * are read once per enum; a case without one falls back to its name.
 */
trait HasLabels
{
    public function label(): string
    {
        return self::labels()[$this->value];
    }

    /**
     * The label of every case, keyed by the case value.
     *
     * @return array<int|string, string>
     */
    public static function labels(): array
    {
        static $labels = null;

        if ($labels === null) {
            $labels = [];
            foreach (self::cases() as $case) {
                $attribute = (new ReflectionClassConstant(self::class, $case->name))->getAttributes(Label::class)[0] ?? null;
                $labels[$case->value] = $attribute?->newInstance()->label ?? $case->name;
            }
        }

        return $labels;
    }
}

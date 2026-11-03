<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

use Attribute;

/**
 * The human-readable label of an enum case, read by HasLabels.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class Label
{
    public function __construct(public string $label) {}
}

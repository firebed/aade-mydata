<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Casts;

/**
 * What a cast targets.
 *
 * @internal
 */
enum Kind
{
    case Scalar;

    case Enum;

    case Model;

    case Collection;
}

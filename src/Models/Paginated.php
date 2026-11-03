<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

/**
 * A myDATA response that comes in parts: when it carries a continuation token, the
 * next part is fetched by passing the token's keys to the same request.
 */
interface Paginated
{
    public function getContinuationToken(): ?ContinuationToken;
}

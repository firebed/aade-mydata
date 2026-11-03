<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

interface ValidatesSchema
{
    /**
     * Validates the model against its myDATA XSD.
     *
     * @return list<array{field: string|null, message: string|null}> The validation errors, empty when the model is valid
     */
    public function validate(): array;
}

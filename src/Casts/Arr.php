<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Casts;

/**
 * Declares a list attribute in a model's casts(): every item of the attribute is
 * cast to the given type, and a single value set on the attribute is appended
 * to the list instead of replacing it. That is how repeated XML elements of the
 * same name (invoice rows, classifications, correlated marks, …) are collected.
 *
 * <pre>
 * protected function casts(): array
 * {
 *     return [
 *         'invoiceDetails'     => Arr::of(InvoiceDetails::class),
 *         'correlatedInvoices' => Arr::of('int'),
 *     ];
 * }
 * </pre>
 */
final readonly class Arr
{
    /**
     * @param  class-string|'int'|'float'|'string'|'bool'  $type
     */
    private function __construct(public string $type) {}

    /**
     * @param  class-string|'int'|'float'|'string'|'bool'  $type  A Model, a ModelCollection, a backed enum, or a scalar type
     */
    public static function of(string $type): self
    {
        return new self($type);
    }
}

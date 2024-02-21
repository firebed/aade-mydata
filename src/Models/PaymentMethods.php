<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Firebed\AadeMyData\Traits\HasFactory;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, PaymentMethodDetail>
 * @implements ArrayAccess<int, PaymentMethodDetail>
 */
class PaymentMethods extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    use HasFactory;
    
    public array $casts = [
        'paymentMethodDetails' => PaymentMethodDetail::class,
    ];

    /**
     * @param PaymentMethodDetail|PaymentMethodDetail[] $paymentMethods
     */
    public function __construct(PaymentMethodDetail|array $paymentMethods = null)
    {
        if ($paymentMethods !== null) {
            $this->attributes['paymentMethodDetails'] = is_array($paymentMethods) ? $paymentMethods : [$paymentMethods];
        }
    }

    public function push($key, $value = null): void
    {
        $this->attributes['paymentMethodDetails'][] = $value;
    }

    public function set($key, $value): void
    {
        $value = is_array($value) ? $value : [$value];
        $this->attributes['paymentMethodDetails'] = $value;
    }

    /**
     * @return Traversable<int, PaymentMethodDetail>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['paymentMethodDetails'] ?? []);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->attributes['paymentMethodDetails']);
    }

    public function offsetGet(mixed $offset): PaymentMethodDetail
    {
        return $this->attributes['paymentMethodDetails'][$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes['paymentMethodDetails'][$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes['paymentMethodDetails'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['paymentMethodDetails']);
    }
}
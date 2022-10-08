<?php

namespace Firebed\AadeMyData\Factories;

class ResponseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'index'       => 1,
            'invoiceUid'  => strtoupper(sha1('invoice-uid')),
            'invoiceMark' => rand(100_000_000_000_000, 999_999_999_999_999),
            'statusCode'  => 'Success'
        ];
    }

    public function uid(string $uid): self
    {
        return $this->state([
            'uid' => strtoupper(sha1($uid))
        ]);
    }

    public function cancelled(): self
    {
        return $this->state([
            'cancellationMark' => rand(100_000_000_000_000, 999_999_999_999_999)
        ])->except(['index', 'invoiceUid', 'invoiceMark']);
    }
}
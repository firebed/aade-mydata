<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

/**
 * The invoice myDATA already holds under the uid of a resent invoice, as error 228 names
 * it ("It has already been sent for another invoice (MARK:…, AUTHENTICATION_CODE:…)").
 *
 * myDATA stores submissions from a queue: until it has stored the invoice, which can take
 * hours, the error names the mark but no authentication code.
 */
final readonly class Duplicate
{
    public function __construct(
        public string $uid,
        public int $mark,
        public ?string $authenticationCode,
    ) {}

    /**
     * Whether myDATA has stored the invoice; until then it gives no authentication code.
     */
    public function isStored(): bool
    {
        return $this->authenticationCode !== null;
    }

    /**
     * The successful response myDATA gave, or would have given, the invoice it holds. The
     * qrUrl is not part of it: myDATA never repeats it.
     */
    public function toResponse(?int $index = null): Response
    {
        return new Response(array_filter([
            'index' => $index,
            'invoiceUid' => $this->uid,
            'invoiceMark' => (string) $this->mark,
            'authenticationCode' => $this->authenticationCode,
            'statusCode' => 'Success',
        ], fn ($value) => $value !== null));
    }
}

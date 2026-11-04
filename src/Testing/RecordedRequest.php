<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Testing;

use DOMDocument;
use Firebed\AadeMyData\Http\MyDataRequest;

/**
 * A request the FakeGateway received.
 */
final class RecordedRequest
{
    private ?DOMDocument $document = null;

    /**
     * @param  'GET'|'POST'  $method
     * @param  array<string, mixed>  $query
     * @param  string|null  $body  The request XML; null for requests sent as a query only
     */
    public function __construct(
        public readonly MyDataRequest $request,
        public readonly string $method,
        public readonly array $query,
        public readonly ?string $body,
    ) {}

    /**
     * The myDATA action, e.g. 'SendInvoices'.
     */
    public function action(): string
    {
        return $this->request->action();
    }

    /**
     * The request XML as a document, null when the request had no body.
     */
    public function document(): ?DOMDocument
    {
        if ($this->body === null || $this->document !== null) {
            return $this->document;
        }

        $this->document = new DOMDocument;
        $this->document->preserveWhiteSpace = false;
        $this->document->loadXML($this->body);

        return $this->document;
    }

    /**
     * How many documents the request sends: the invoices of a SendInvoices, the
     * classifications of a SendIncomeClassification, … (the children of the root element).
     */
    public function count(): int
    {
        return $this->document()?->documentElement->childElementCount ?? 0;
    }
}

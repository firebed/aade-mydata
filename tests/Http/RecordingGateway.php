<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Http\Gateway;
use Firebed\AadeMyData\Http\MyDataRequest;

class RecordingGateway implements Gateway
{
    public ?MyDataRequest $request = null;

    public ?array $query = null;

    public ?string $body = null;

    public function __construct(private ?string $responseXml = null) {}

    public function get(MyDataRequest $request, array $query): string
    {
        $this->request = $request;
        $this->query = $query;
        $this->body = null;

        return $this->responseXml ?? throw new LogicException('No response XML configured for GET.');
    }

    public function post(MyDataRequest $request, ?array $query = null, ?string $body = null): string
    {
        $this->request = $request;
        $this->query = $query;
        $this->body = $body;

        return $this->responseXml ?? '<?xml version="1.0" encoding="utf-8"?><ResponseDoc><response><index>1</index><invoiceMark>400001</invoiceMark><statusCode>Success</statusCode></response></ResponseDoc>';
    }
}

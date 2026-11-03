<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;

/**
 * Carries a myDATA request to a remote system and returns the raw response XML.
 *
 * The default implementation (GuzzleGateway) talks to the AADE myDATA REST API.
 * Register an alternative with MyDataRequest::setGateway() to route requests
 * elsewhere (for example through an e-invoicing provider) without changing
 * the code that builds and sends the requests.
 */
interface Gateway
{
    /**
     * @param  array<string, mixed>  $query
     *
     * @throws MyDataException
     */
    public function get(MyDataRequest $request, array $query): string;

    /**
     * @param  array<string, mixed>|null  $query
     *
     * @throws MyDataException
     */
    public function post(MyDataRequest $request, ?array $query = null, ?string $body = null): string;
}

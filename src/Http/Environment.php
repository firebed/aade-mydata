<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use InvalidArgumentException;

enum Environment: string
{
    case Development = 'dev';

    case Production = 'prod';

    /**
     * Accepts the enum, or 'dev' / 'prod' in any case.
     */
    public static function parse(self|string $environment): self
    {
        if ($environment instanceof self) {
            return $environment;
        }

        return self::tryFrom(strtolower($environment))
            ?? throw new InvalidArgumentException("Unknown myDATA environment '$environment', expected 'dev' or 'prod'.");
    }

    public function isProduction(): bool
    {
        return $this === self::Production;
    }

    /**
     * The base URL of the myDATA REST API for the ERP channel, or the provider channel.
     */
    public function baseUrl(bool $provider = false): string
    {
        return match ($this) {
            self::Production => $provider ? 'https://mydatapi.aade.gr/myDataProvider/' : 'https://mydatapi.aade.gr/myDATA/',
            self::Development => $provider ? 'https://mydataapidev.aade.gr/myDataProvider/' : 'https://mydataapidev.aade.gr/',
        };
    }
}

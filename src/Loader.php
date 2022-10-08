<?php

namespace Firebed\AadeMyData;

use Error;

class Loader
{
    private static array $map;

    public static function getUrl(string $class, string $env)
    {
        self::loadMap($env);

        if (!array_key_exists($class, self::$map[$env])) {
            throw new Error("Unspecified URL for " . basename($class));
        }

        return self::$map[$env][$class];
    }

    private static function loadMap(string $env): void
    {
        if (self::mapLoaded()) {
            return;
        }

        self::$map = require __DIR__ . '/../config/urls.php';

        if (!array_key_exists($env, self::$map)) {
            throw new Error("Invalid environment '$env'. Please invoke MyDataRequest::setEnvironment to set the environment value, possible values are [dev,prod].");
        }
    }

    private static function mapLoaded(): bool
    {
        return isset(self::$map);
    }
}
<?php

namespace Tests\Traits;

trait UsesStubs
{
    protected function stubsPath($path): string
    {
        return __DIR__ . "/../../stubs" . ($path ? "/$path" : '');
    }

    protected function getStub(string $name): string
    {
        return file_get_contents($this->stubsPath($name.(str_ends_with($name, '.xml') ? '' : '.xml')));
    }
}
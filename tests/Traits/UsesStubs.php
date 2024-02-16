<?php

namespace Tests\Traits;

trait UsesStubs
{
    private function getStub(string $name): string
    {
        return file_get_contents(__DIR__."/../../stubs/$name.xml");
    }
}
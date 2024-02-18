<?php

namespace Tests\Http;

use Firebed\AadeMyData\Http\MyDataRequest;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

abstract class MyDataHttpTestCase extends TestCase
{
    use UsesStubs;

    protected function setUp(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
    }
}
<?php

namespace Tests\Http;

use Firebed\AadeMyData\Http\MyDataRequest;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

abstract class MyDataHttpTestCase extends TestCase
{
    use UsesStubs;

    protected function initErpDev(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
    }

    protected function initErpProd(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod');
    }

    protected function initProviderDev(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
    }

        protected function initProviderProd(): void
        {
            MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        }

    protected function setUp(): void
    {
        $this->initErpDev();
    }
}
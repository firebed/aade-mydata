<?php

namespace Tests\Http\DigitalGoodsMovement;

use Tests\Http\MyDataHttpTestCase;

class DigitalGoodsMovementTestCase extends MyDataHttpTestCase
{
    protected function stubsPath($path): string
    {
            return __DIR__ . "/../../../stubs/digital-goods-movement" . ($path ? "/$path" : '');
    }
}
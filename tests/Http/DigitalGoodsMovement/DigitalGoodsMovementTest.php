<?php

namespace Tests\Http\DigitalGoodsMovement;

use Tests\Http\MyDataHttpTestCase;

class DigitalGoodsMovementTest extends MyDataHttpTestCase
{
    protected function stubsPath($path): string
    {
            return __DIR__ . "/../../../stubs/digital-goods-movement" . ($path ? "/$path" : '');
    }
}
<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * @extends TypeArray<Response>
 * @version 2.0.1
 */
class ResponseDoc extends TypeArray
{
    protected array $casts = [
        'response' => Response::class,
    ];

    public function __construct()
    {
        parent::__construct('response');
    }
}
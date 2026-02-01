<?php

namespace Firebed\AadeMyData\Models;

interface ValidatesSchema
{
    public function validate(): array;
}
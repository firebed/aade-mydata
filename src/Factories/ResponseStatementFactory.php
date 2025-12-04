<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Statements\ResponseStatement;

/**
 * @extends Factory<ResponseStatement>
 */
class ResponseStatementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'index'       => 1,
            'statementId' => (string) fake()->randomNumber(3),
            'statusCode'  => 'Success',
        ];
    }
}

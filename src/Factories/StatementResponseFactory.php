<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Statements\StatementResponse;

/**
 * @extends Factory<StatementResponse>
 */
class StatementResponseFactory extends Factory
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

<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Enums\ProviderType;
use Firebed\AadeMyData\Models\Statements\Statement;

/**
 * @extends Factory<Statement>
 */
class StatementFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-2 years', '-1 year');
        $stop  = fake()->boolean() ? fake()->dateTimeBetween('-11 months', 'now') : null;

        return [
            'statementId'                    => fake()->randomNumber(3),
            'submissionDateTime'             => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d\TH:i:s'),
            'entityVatNumber'                => fake()->numerify('#########'),
            'liableUserCategory'             => fake()->randomElement(ProviderType::cases()),
            'providerType'                   => fake()->randomElement(ProviderType::cases()),
            'isB2BTransactions'              => fake()->boolean(),
            'isB2CTransactions'              => fake()->boolean(),
            'isB2GTransactions'              => fake()->boolean(),
            'providerVatNumber'              => fake()->numerify('#########'),
            'providerLicenceNumber'          => (string)fake()->numberBetween(1000, 999999),
            'providerContractNumber'         => (string)fake()->numberBetween(1000, 999999),
            'providerContractConclusionDate' => fake()->dateTimeBetween('-3 years', '-2 years')->format('Y-m-d\TH:i:s'),
            'providerContractActivationDate' => fake()->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d\TH:i:s'),
            'issueStartDate'                 => $start->format('Y-m-d'),
            'issueStopDate'                  => $stop?->format('Y-m-d'),
            'internetProvider'               => fake()->company(),
            'internetProviderContractNumber' => (string)fake()->numberBetween(1000, 999999),
            'internetProviderContractDate'   => fake()->dateTimeBetween('-3 years', '-1 years')->format('Y-m-d\TH:i:s'),
        ];
    }
}

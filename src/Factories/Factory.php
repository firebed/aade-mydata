<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Factories;

use Faker\Generator;
use Firebed\AadeMyData\Models\Model;
use LogicException;

/**
 * @template TModel of Model
 */
abstract class Factory
{
    private static Generator $faker;

    /** @var class-string<TModel> */
    protected string $modelName;

    /** @var array<string, mixed> */
    protected array $state = [];

    /** @var list<string> */
    protected array $except = [];

    protected int $count = 1;

    /**
     * The factory of a model, by name: the model's class name followed by "Factory", in this namespace.
     *
     * @template T of Model
     *
     * @param  class-string<T>  $modelName
     * @return Factory<T>
     */
    public static function factoryForModel(string $modelName, int $count = 1): Factory
    {
        $factoryModelName = substr($modelName, strrpos($modelName, '\\') + 1);

        $factoryName = __NAMESPACE__.'\\'.$factoryModelName.'Factory';

        /** @var Factory<T> $factory The factory is named after the model, which the naming convention guarantees */
        $factory = self::newFactory($factoryName);
        $factory->modelName = $modelName;
        $factory->count = $count;

        return $factory;
    }

    /**
     * @return Factory<Model>
     */
    public static function newFactory(string $factoryName): Factory
    {
        $factory = new $factoryName;

        if (! $factory instanceof self) {
            throw new LogicException("$factoryName is not a model factory.");
        }

        return $factory;
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return TModel|list<TModel> One model, or a list of them when the factory was made with a count other than 1
     */
    public function make(array $attributes = [])
    {
        $instances = [];
        for ($i = 0; $i < $this->count; $i++) {
            $attributes = array_merge($this->definition(), $attributes, $this->state);

            if (! empty($this->except)) {
                $attributes = array_diff_key($attributes, array_flip($this->except));
            }

            $instances[] = $this->modelName::fromArray(array_map(fn ($value) => $value instanceof Factory ? $value->make() : $value, $attributes));
        }

        return $this->count === 1 ? $instances[0] : $instances;
    }

    /**
     * @param  list<string>  $fields
     */
    public function except(array $fields): static
    {
        $this->except = $fields;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function state(array $attributes): static
    {
        $this->state = array_merge($attributes);

        return $this;
    }

    public static function fake(): Generator
    {
        return self::$faker ??= \Faker\Factory::create();
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function definition(): array;
}

if (! function_exists('fake')) {
    function fake(): Generator
    {
        return Factory::fake();
    }
}

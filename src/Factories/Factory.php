<?php

namespace Firebed\AadeMyData\Factories;

use Faker\Generator;
use Firebed\AadeMyData\Models\Type;

/**
 * @template TModel of Type
 */
abstract class Factory
{
    protected string $modelName;
    protected array  $state = [];
    protected array  $except = [];
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public static function factoryForModel($modelName): Factory
    {
        $namespace = dirname(__NAMESPACE__);
        $factoryName = $namespace . '\\Factories\\' . basename($modelName) . 'Factory';

        $factory = self::newFactory($factoryName);
        $factory->modelName = $modelName;
        return $factory;
    }

    public static function newFactory($factoryName): Factory
    {
        return new $factoryName();
    }

    /**
     * @return TModel
     */
    public function make(array $attributes = [])
    {
        $attributes = array_merge($this->definition(), $attributes, $this->state);

        if (!empty($this->except)) {
            $attributes = array_diff_key($attributes, array_flip($this->except));
        }

        $instance = new $this->modelName();
        $instance->setAttributes($attributes);
        return $instance;
    }

    public function except(array $fields): self
    {
        $this->except = $fields;
        
        return $this;
    }

    public function state(array $attributes): self
    {
        $this->state = array_merge($attributes);
        
        return $this;
    }

    abstract public function definition(): array;
}
<?php

namespace Firebed\AadeMyData\Factories;

use Faker\Generator;
use Firebed\AadeMyData\Models\Type;

/**
 * @template TModel of Type
 */
abstract class Factory
{
    private static Generator $faker;

    protected string $modelName;
    protected array  $state  = [];
    protected array  $except = [];
    protected int    $count  = 1;

    /**
     * @param string $modelName
     * @param int $count
     * @return Factory
     */
    public static function factoryForModel(string $modelName, int $count = 1): Factory
    {
        $namespace = dirname(__NAMESPACE__);
        echo PHP_EOL;
        echo __NAMESPACE__ . PHP_EOL;
        echo $modelName . PHP_EOL;
        echo $namespace . PHP_EOL;
        echo basename($modelName) . PHP_EOL;
        
        echo PHP_EOL;
        echo PHP_EOL;
        
        $factoryName = $namespace.'\\Factories\\'.basename($modelName).'Factory';

        $factory = self::newFactory($factoryName);
        $factory->modelName = $modelName;
        $factory->count = $count;
        return $factory;
    }

    public static function newFactory($factoryName): Factory
    {
        return new $factoryName();
    }

    /**
     * @return TModel[]|TModel
     */
    public function make(array $attributes = [])
    {
        $instances = [];
        for ($i = 0; $i < $this->count; $i++) {
            $attributes = array_merge($this->definition(), $attributes, $this->state);

            if (!empty($this->except)) {
                $attributes = array_diff_key($attributes, array_flip($this->except));
            }

            $instance = new $this->modelName();

            foreach ($attributes as $key => $value) {
                if ($value instanceof Factory) {
                    $value = $value->make();
                }

                $instance->set($key, $value);
            }

            $instances[] = $instance;
        }

        return $this->count === 1 ? $instances[0] : $instances;
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

    public static function fake(): Generator
    {
        return self::$faker ??= \Faker\Factory::create();
    }

    abstract public function definition(): array;
}

if (!function_exists('fake')) {
    function fake(): Generator
    {
        return Factory::fake();
    }
}
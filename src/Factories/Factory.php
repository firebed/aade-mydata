<?php

namespace Firebed\AadeMyData\Factories;

abstract class Factory
{
    private string $modelName;
    private array  $state = [];
    private array  $except = [];

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
        return new $factoryName;
    }

    public function make(array $attributes = [])
    {
        $attributes = array_merge($this->definition(), $attributes, $this->state);

        if (!empty($this->except)) {
            $attributes = array_diff_key($attributes, array_flip($this->except));
        }

        $instance = new $this->modelName;
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

    public abstract function definition(): array;
}
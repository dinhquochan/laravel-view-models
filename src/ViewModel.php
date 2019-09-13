<?php

namespace DinhQuocHan\ViewModels;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

abstract class ViewModel implements Arrayable
{
    use Macroable;

    /**
     *  The ignored public methods.
     *
     * @var array
     */
    protected $ignore = [];

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items()->all();
    }

    /**
     * Get model view variables.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function items()
    {
        $class = new ReflectionClass($this);

        $publicProperties = collect($class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->reject(function (ReflectionProperty $property) {
                return $this->shouldIgnore($property->getName());
            })
            ->mapWithKeys(function (ReflectionProperty $property) {
                return [$property->getName() => $this->{$property->getName()}];
            });

        $publicMethods = collect($class->getMethods(ReflectionMethod::IS_PUBLIC))
            ->reject(function (ReflectionMethod $method) {
                return $this->shouldIgnore($method->getName());
            })
            ->mapWithKeys(function (ReflectionMethod $method) {
                return [$method->getName() => $this->createVariableFromMethod($method)];
            });

        $macroMethods = collect(static::$macros)
            ->reject(function ($macro, $method) {
                return $this->shouldIgnore($method);
            })
            ->mapWithKeys(function ($macro, $method) {
                return [$method => $this->{$method}()];
            });

        return $publicProperties->merge($publicMethods)->merge($macroMethods);
    }

    /**
     * Determine if method's name should be ignored.
     *
     * @param  string  $methodName
     * @return bool
     */
    protected function shouldIgnore($methodName)
    {
        if (Str::startsWith($methodName, '__')) {
            return true;
        }

        return in_array($methodName, $this->ignoredMethods(), true);
    }

    /**
     * Get ignored methods.
     *
     * @return array
     */
    protected function ignoredMethods()
    {
        return array_merge(['toArray'], $this->ignore);
    }

    /**
     * Create variable from method.
     *
     * @param  \ReflectionMethod  $method
     * @return mixed
     */
    protected function createVariableFromMethod($method)
    {
        if ($method->getNumberOfParameters() === 0) {
            return $this->{$method->getName()}();
        }

        return Closure::fromCallable([$this, $method->getName()]);
    }
}

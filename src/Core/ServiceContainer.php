<?php

namespace Sh1ne\MySqlBot\Core;

use InvalidArgumentException;
use ReflectionClass;

class ServiceContainer
{

    protected static ?ServiceContainer $instance = null;

    protected array $singletonInstances = [];

    protected array $singletonServices = [];

    protected array $registeredServices = [];

    public static function instance() : static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @template T
     * @param class-string<T> $abstract
     * @param T $instance
     *
     * @return void
     */
    public function singletonByInstance(string $abstract, $instance) : void
    {
        $this->singletonInstances[$abstract] = $instance;
    }

    /**
     * @template T
     * @template F of T
     * @param class-string<T> $abstract
     * @param class-string<F> $concrete
     *
     * @return void
     */
    public function singleton(string $abstract, string $concrete) : void
    {
        $this->singletonServices[$abstract] = $concrete;
    }

    /**
     * @template T
     * @template F of T
     * @param class-string<T> $abstract
     * @param class-string<F> $concrete
     *
     * @return void
     */
    public function register(string $abstract, string $concrete) : void
    {
        $this->registeredServices[$abstract] = $concrete;
    }

    /**
     * @template T
     * @template F of T
     * @param class-string<T> $abstract
     *
     * @return F
     */
    public function get(string $abstract)
    {
        if (isset($this->singletonInstances[$abstract])) {
            return $this->singletonInstances[$abstract];
        }

        if (isset($this->registeredServices[$abstract])) {
            $concrete = $this->registeredServices[$abstract];

            return $this->instantiateConcrete($concrete);
        }

        if (isset($this->singletonServices[$abstract])) {
            $concrete = $this->singletonServices[$abstract];

            $instance = $this->instantiateConcrete($concrete);

            $this->singletonByInstance($abstract, $instance);

            unset($this->singletonServices[$abstract]);

            return $instance;
        }

        return $this->instantiateConcrete($abstract);
    }

    private function instantiateConcrete(string $concrete) : mixed
    {
        if (!class_exists($concrete)) {
            throw new InvalidArgumentException("Class '$concrete' does not exist");
        }

        // TODO: think about optimization (reflection is slow)

        $reflection = new ReflectionClass($concrete);

        if ($reflection->isAbstract()) {
            throw new InvalidArgumentException("Class '$concrete' cannot be instantiated as it is abstract");
        }

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return new $concrete();
        }

        $values = [];

        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            // TODO: add some validation for getType()

            $values[] = $this->get($parameter->getType()->getName());
        }

        return new $concrete(...$values);
    }

}
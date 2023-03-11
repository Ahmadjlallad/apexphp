<?php
declare(strict_types=1);

namespace Apex\src;

class Container
{
    protected array $bindings = [];
    protected array $resolved = [];

    public function has(string $serviceName): bool
    {
        return $this->bindings[$serviceName];
    }

    public function resolved(string $serviceName): bool
    {
        return isset($this->resolved[$serviceName]);
    }

    public function resolve(string $serviceName): mixed
    {
        return $this->resolved[$serviceName] ?? ($this->resolved[$serviceName] = call_user_func($this->bindings[$serviceName]));
    }

    public function bind(string $serviceName, $handleFun): void
    {
        $this->bindings[$serviceName] = $handleFun;
    }
}
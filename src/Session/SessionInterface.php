<?php

namespace Apex\src\Session;

interface SessionInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * @param string $key
     * @param mixed $value
     * @return SessionInterface
     */
    public function set(string $key, mixed $value): self;

    public function remove(string $key): void;

    public function clear(): void;

    public function has(string $key): bool;
}

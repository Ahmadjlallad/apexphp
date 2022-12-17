<?php

namespace Apex\src\Router;

interface RouterInterface
{
    public function get(string $path, array|string|callable|null $action): void;

    public function post(string $path, array|string|callable|null $action): void;
}
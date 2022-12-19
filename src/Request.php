<?php
declare(strict_types=1);

namespace Apex\src;

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

class Request
{
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if (!$position) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function input(string $prop = ''): array|null
    {
        $body = $_POST;
        $inputType = INPUT_POST;

        if ($this->getHttpMethod() !== 'post') {
            $body = $_GET;
            $inputType = INPUT_GET;
        }
        if (!empty($prop)) {
            $prop = is_array($prop) ? $prop : [$prop];
            $body = array_filter($body, fn($key) => in_array($key, $prop), ARRAY_FILTER_USE_KEY);
        }
        $res = [];

        foreach ($body as $key => $value) {
            $res[$key] = filter_input($inputType, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $res;
    }

    public function getHttpMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function validate(array $data, array $rules, array $messages = []): Validation
    {
        return (new Validator)->validate($data, $rules, $messages);
    }

    public function isPost(): bool
    {
        return $this->getHttpMethod() === 'post';
    }

    public function isGet(): bool
    {
        return $this->getHttpMethod() === 'get';
    }
}
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

    public function getHttpMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getParams(string $prop = ''): ?array
    {
        if ($this->getHttpMethod() !== 'get') {
            return null;
        }

        $body = $_GET;
        if (!empty($prop)) {
            $prop = is_array($prop) ? $prop : [$prop];
            $body = array_filter($body, fn($key) => in_array($key, $prop), ARRAY_FILTER_USE_KEY);
        }
        $res = [];

        foreach ($body as $key => $value) {
            $res[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $res;
    }

    public function postParams(string $prop = ''): array|null
    {
        if ($this->getHttpMethod() !== 'post') {
            return null;
        }

        $body = $_POST;
        if (!empty($prop)) {
            $prop = is_array($prop) ? $prop : [$prop];
            $body = array_filter($body, fn($key) => in_array($key, $prop), ARRAY_FILTER_USE_KEY);
        }
        $res = [];

        foreach ($body as $key => $value) {
            $res[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $res;
    }

    public function validate(array $data, array $rules, array $messages = []): Validation
    {
        return (new Validator)->validate($data, $rules, $messages);
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function getValidator(Validator $validator): Validator
    {
        return $validator;
    }
}
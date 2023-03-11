<?php
declare(strict_types=1);

namespace Apex\src;

use Apex\src\Model\Validation\Validator;
use Apex\src\Session\Session;
use Rakit\Validation\Validation;

class Request
{
    private Session $session;

    public function __construct()
    {
        $this->session = app()->session;
    }

    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if (!$position) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function validateWithBagName(string $name, array $data, array $rules, array $messages = []): bool|Response
    {
        $validation = (new Validator)->validate($data, $rules, $messages);
        if ($validation->fails()) {
            $this->session->setFlash($name, $validation->errors());
            $response = app()->response->back();
            $response->sendHeadersImmediately();
        }
        return true;
    }

    public function sessionValidate(array $data, array $rules, array $messages = []): bool|Response
    {
        $validation = (new Validator)->validate($data, $rules, $messages);
        if ($validation->fails()) {
            $this->session->setFlash('errors', $validation->errors());
            $this->session->setFlash('params', $this->input());
            $response = app()->response->back();
            $response->sendHeadersImmediately();
        }
        return true;
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
<?php
declare(strict_types=1);

namespace Apex\src;

use Apex\src\Model\Validation\Validator;
use Apex\src\Router\Methods;
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

    public function validate(array $data, array $rules, array $messages = []): Validation
    {
        return (new Validator)->validate($data, $rules, $messages);
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

    public function input(string|array $prop = ''): array|string|null
    {
        $inputType = INPUT_POST;
        $res = [];
        if ($this->getHttpMethod() === Methods::GET->value) {
            $body = $_GET;
            $inputType = INPUT_GET;
        }
        //@todo test this
        $res = $this->filterInputArrayWithDefaultFlags($inputType, FILTER_DEFAULT);
        if (!empty($prop)) {
            $body = array_filter($res, fn($key) => in_array($key, is_array($prop) ? $prop : [$prop]), ARRAY_FILTER_USE_KEY);
            if (empty($body)) {
                return null;
            }
            if (is_string($prop)) {
                return $res[$prop];
            }
            return $body;
        }
        return $res;
    }

    public function getHttpMethod(): string
    {
        $method = Methods::upperCase($_SERVER['REQUEST_METHOD']);
        if ($method === Methods::POST->value) {
            $method = Methods::upperCase($this->postInput('_method')) ?? Methods::POST->value;
        }
        return $method;
    }

    public function postInput(string|array $search = ''): mixed
    {
        $res = $this->filterInputArrayWithDefaultFlags(INPUT_POST, FILTER_DEFAULT);
        if (!empty($search)) {
            if (is_string($search)) {
                return $res[$search] ?? null;
            }
            return $res ?? [];
        }
        return null;
    }

    function filterInputArrayWithDefaultFlags($type, $filter, $flags = [], $add_empty = true): false|array|null
    {
        $loopThrough = array();
        switch ($type) {
            case INPUT_GET :
                $loopThrough = $_GET;
                break;
            case INPUT_POST :
                $loopThrough = $_POST;
                break;
            case INPUT_COOKIE :
                $loopThrough = $_COOKIE;
                break;
            case INPUT_SERVER :
                $loopThrough = $_SERVER;
                break;
            case INPUT_ENV :
                $loopThrough = $_ENV;
                break;
        }

        $args = array();
        foreach ($loopThrough as $key => $value) {
            $args[$key] = array('filter' => $filter, 'flags' => $flags);
        }

        return filter_input_array($type, $args, $add_empty);
    }

    public function isPost(): bool
    {
        return $this->getHttpMethod() === Methods::POST->value;
    }

    public function isGet(): bool
    {
        return $this->getHttpMethod() === Methods::GET->value;
    }
}
<?php

namespace Apex\src\Router;

enum Methods: string
{
    case DELETE = 'DELETE';
    case POST = 'POST';
    case GET = 'GET';
    case PUT = 'PUT';

    public static function upperCase(mixed $input): ?string
    {
        return match (strtolower($input)) {
            'get' => self::GET->value,
            'delete' => self::DELETE->value,
            'put' => self::PUT->value,
            'post' => self::POST->value,
            default => null
        };
    }
}

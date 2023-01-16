<?php

namespace Apex\src;

class Response
{
    public function setStatus(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): void
    {
        header("location: $url");
    }
}
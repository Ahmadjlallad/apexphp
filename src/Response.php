<?php
declare(strict_types=1);

namespace Apex\src;

use Error;

class Response
{
    private const VIEW = 'VIEW';
    private const JSON = 'JSON';
    public readonly string $responseType;
    public string|array $content;

    public static function makeResponse(array|bool|string $content): Response
    {
        if (empty($content)) {
            throw new Error("view doesn't Exist");
        }
        $response = new Response;
        $response->content = $content;
        $response->responseType = is_array($content) ? self::JSON : self::VIEW;
        return $response;
    }

    public function setStatus(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): static
    {
        header("location: $url");
        return $this;
    }

    public function back()
    {
        header('location: ' . '/register');
    }

    public function processResponse(): void
    {
        $content = $this->content;
        if ($this->responseType === self::JSON) {
            header('Content-type: application/json');
            $content = json_encode($content);
        }
        echo $content;
    }
}
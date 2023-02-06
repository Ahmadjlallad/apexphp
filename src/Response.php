<?php
declare(strict_types=1);

namespace Apex\src;

use Error;

class Response
{
    private const VIEW = 'VIEW';
    private const JSON = 'JSON';
    private const REDIRECT = 'REDIRECT';
    public readonly string $responseType;
    public string|array $content;
    public array $headers = [];

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

    public function back(): static
    {
        return $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function redirect(string $url, int $code = 307): static
    {
        $this->setStatus($code);
        $this->responseType = self::REDIRECT;
        $this->headers['location'] = $url;
        return $this;
    }

    public function setStatus(int $code): void
    {
        http_response_code($code);
    }

    public function processResponse(): void
    {
        if ($this->responseType === self::REDIRECT) {
            $this->makeHeaders();
            return;
        }
        $content = $this->content;
        if ($this->responseType === self::JSON) {
            header('Content-type: application/json');
            $content = json_encode($content);
        }
        echo $content;
    }

    protected function makeHeaders(): void
    {
        $headers = implode(';', array_map(fn($key, $value) => "$key:$value", array_keys($this->headers), array_values($this->headers)));
        header($headers);
    }
}
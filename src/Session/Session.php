<?php

namespace Apex\src\Session;

use Apex\src\App;

class Session implements SessionInterface
{
    protected const FLASH_MESSAGES = 'FLASH_MESSAGES';

    public function __construct(public bool $haveSession = true, ?string $cacheExpire = null, ?string $cacheLimiter = null)
    {
        if ($haveSession) {
            $dirName = realpath(App::$ROOT_DIR) . '/runtime/session';
            if (!file_exists($dirName)) {
                mkdir($dirName, 0777, true);
            }
            ini_set('session.save_path', $dirName);
            $this->boot($cacheExpire, $cacheLimiter);
        } else {
            session_unset();
        }
    }

    private function boot(?string $cacheExpire = null, ?string $cacheLimiter = null): void
    {
        if (!$this->isStarted()) {
            if ($cacheLimiter !== null) {
                session_cache_limiter($cacheLimiter);
            }

            if ($cacheExpire !== null) {
                session_cache_expire($cacheExpire);
            }
            session_start();
        }
        if (!empty($_SESSION[self::FLASH_MESSAGES])) {
            $flashMessages = &$_SESSION[self::FLASH_MESSAGES];
            foreach ($flashMessages as &$flashMessage) {
                $flashMessage['toBeRemoved'] = true;
            }
        }
    }

    public function isStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function setFlash(string $key, mixed $message): void
    {
        $_SESSION[self::FLASH_MESSAGES][$key] = ['value' => $message, 'toBeRemoved' => false];
    }

    public function getFlash(string $key, mixed $default = null): mixed
    {
        return $_SESSION[self::FLASH_MESSAGES][$key]['value'] ?? $default;
    }

    public function __destruct()
    {
        if ($this->haveSession) {
            if (!empty($_SESSION[self::FLASH_MESSAGES])) {
                $flashMessages = &$_SESSION[self::FLASH_MESSAGES];
                foreach ($flashMessages as $key => &$flashMessage) {
                    if ($flashMessage['toBeRemoved']) {
                        unset($flashMessages[$key]);
                    }
                }
            }
        }
    }

    public function __get(string $name)
    {
        if (method_exists($this, $methodName = ('get' . ucfirst($name)))) {
            return $this->{$methodName}();
        }
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Session
     */
    public function set(string $key, mixed $value): SessionInterface
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function clear(): void
    {
        session_unset();
    }

    public function flashMessages(): array
    {
        return $_SESSION[self::FLASH_MESSAGES] ?? [];
    }

    public function id(): false|string
    {
        return session_id();
    }
}
<?php

namespace Apex\src\Session;

class Session
{
    protected array $session;
    protected const FLASH_MESSAGES = 'FLASH_MESSAGES';

    public function __construct()
    {
//        session_reset();
        session_start();
        $this->session = &$_SESSION;
        foreach (($this->session[self::FLASH_MESSAGES] ?? []) as &$flashMessage) {
            $flashMessage['toBeRemoved'] = true;
        }
    }

    public function setFlash(string $key, mixed $message): void
    {
        $this->session[self::FLASH_MESSAGES][$key] = ['value' => $message, 'toBeRemoved' => false];
    }

    public function getFlash(string $key): mixed
    {
        return $this->session[self::FLASH_MESSAGES][$key]['value'];
    }

    public function __destruct()
    {

    }

    public function &__get(string $name)
    {
        if (method_exists($this, $methodName = ('get' . ucfirst($name)))) {
            return $this->{$methodName}();
        }
    }
}
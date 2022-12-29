<?php

namespace Apex\src\Model;

class ErrorBag extends \Rakit\Validation\ErrorBag
{
    public function addError(string $key, string $message): static
    {
        parent::add($key, '*', $message);
        return $this;
    }

    public function margeErrorBadges(ErrorBag|\Rakit\Validation\ErrorBag $errorBag): static
    {
        foreach ($errorBag->toArray() as $attribute => $errorMessages) {
            foreach ($errorMessages as $rule => $value) {
                $this->add($attribute, $rule, $value);
            }
        }
        return $this;
    }
}
<?php

namespace Apex\src\Views\Forms;

use Apex\src\Model\Model;

class Form
{
    /**
     * @param $config array<{string: string, string:action, array:options}>
     * @return Form
     */
    public static function begin(array $config): Form
    {
        $options = implode(' ', array_map(fn($key, $value): string => "$key='$value'", array_keys($config['options']), array_values($config['options'])));
        echo sprintf('<form action="%s" method="%s" %s>', $config['action'] ?? '', $config['method'], $options);
        return new Form();
    }

    public static function end(): string
    {
        return '</form>';
    }

    public function field(Model $model, $attr, array $options): Field
    {
        return new Field($model, $attr, $options);
    }
}
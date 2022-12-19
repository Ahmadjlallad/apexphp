<?php

namespace Apex\src\View\Forms;

use Apex\src\Model\Model;

class Form
{
    public static function begin(string $method, string $action = ''): Form
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
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
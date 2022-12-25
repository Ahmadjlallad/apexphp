<?php

namespace Apex\src\Database\Migration\MigrationsTrait;


trait Sanitize
{
    protected function sanitizeString(string $v)
    {
        return filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    protected function sanitizeInt(int $v)
    {
        return filter_var($v, FILTER_SANITIZE_NUMBER_INT);
    }
}
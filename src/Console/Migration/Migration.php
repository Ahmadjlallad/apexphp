<?php

namespace Apex\src\Console\Migration;

use Symfony\Component\Console\Command\Command;

abstract class Migration extends Command
{
    public function __construct()
    {
        $array = explode('\\', static::class);
        parent::__construct('migration:' . strtolower(end($array)));
    }
}
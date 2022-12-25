<?php

namespace Apex\src\Database\Migration\Schema\Definition;

enum DefaultType
{
    case STRING;
    case BUILTIN;
    case INT;
}
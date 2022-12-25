<?php

namespace Apex\src\Database\Query;

abstract class ColumnBlueprint
{
    public string $Field;
    public string $Type;
    public string $Null;
    public string $KeyDefault;
    public string $Extra;
}
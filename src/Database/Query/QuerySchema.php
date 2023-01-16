<?php

namespace Apex\src\Database\Query;

enum QuerySchema
{
    case INSERT;
    case INTO;
    case INSERT_INTO;
    case VALUES;
}

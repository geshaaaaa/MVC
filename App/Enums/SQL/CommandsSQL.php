<?php

namespace App\Enums\SQL;

enum CommandsSQL: string
{
    case IS = 'IS';
    case IS_NOT = 'IS NOT';
    case IN = 'IN';
    case NOT_IN = 'NOT IN';
    case LIKE = 'LIKE';
    case NOT_LIKE = 'NOT LIKE';
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GREATER = '>';
    case GREATER_EQUAL = '>=';
    case LESS = '<';
    case LESS_EQUAL = '<=';
    case NULL = 'NULL';


}

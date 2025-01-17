<?php

namespace Core;

use Core\Traits\Queryable;
class Model
{
    public int $id;

    use Queryable;
}
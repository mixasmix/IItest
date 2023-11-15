<?php

declare(strict_types = 1);

namespace App\Tasks;

class TaskOne
{
    public ?TaskOne $next;
    public int $x;
    public function __construct(int $x)
    {
        $this->x = $x;
    }
}

<?php

namespace App\Enums;

enum UserAction: int
{
    case CLICK = 1;
    case LIKE = 2;
    case READ = 3;

    public function name(): string
    {
        return match ($this) {
            self::CLICK => 'click',
            self::LIKE => 'like',
            self::READ => 'read',
        };
    }
}

<?php

namespace App\Enums;

enum PlatformType: int
{
    case WEB = 1;
    case ANDROID = 2;
    case IOS = 3;

    public function name(): string
    {
        return match ($this) {
            self::WEB => 'web',
            self::ANDROID => 'android',
            self::IOS => 'ios',
        };
    }
}

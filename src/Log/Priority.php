<?php

namespace BxF\Log;

enum Priority
{
    case Verbose;
    case Info;
    case Warning;
    case Error;
    
    public function rank(): int
    {
        return match ($this)
        {
            self::Verbose => 1,
            self::Info => 2,
            self::Warning => 3,
            self::Error => 4,
        };
    }
}

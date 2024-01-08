<?php

namespace BxF\TypeConverter;

class FromString
{
    public static function toInt(?string $value): ?int
    {
        if($value === null)
            return null;
        
        return intval($value);
    }
}
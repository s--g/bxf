<?php

namespace BxF\Http;

enum Method
{
    case GET;
    case POST;
    case PUT;
    
    public static function fromString(string $method): ?Method
    {
        return match ($method)
        {
            'GET' => self::GET,
            'POST' => self::POST,
            'PUT' => self::PUT,
            default => null,
        };
    }
}
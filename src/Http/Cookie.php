<?php

namespace BxF\Http;

use BxF\PropertyAccess;

class Cookie
{
    use PropertyAccess;
    
    protected string $name;
    
    protected string $value;
    
    protected int $hours;
    
    protected bool $secure;
    
    protected bool $httpOnly;
    
    public function __construct(string $name, string $value, int $hours = 1, bool $secure = false, bool $httpOnly = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->hours = $hours;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }
}
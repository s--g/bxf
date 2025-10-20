<?php

namespace BxF\Http;

use BxF\PropertyAccess;

/**
 * @method string getName()
 * @method $this setName(string $value)
 *
 * @method string getValue()
 * @method $this setValue(string $value)
 *
 * @method int getHours()
 * @method $this setHours(int $value)
 *
 * @method $this setSecure(bool $value)
 *
 * @method $this setHttpOnly(bool $value)
 */
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
    
    public function isSecure(): bool
    {
        return $this->secure;
    }
    
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }
}
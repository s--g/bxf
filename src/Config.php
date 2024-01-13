<?php

namespace BxF;

class Config
{
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    public function get($key)
    {
        if(isset($this->config[$key]))
        {
            if(is_array($this->config[$key]))
                return new self($this->config[$key]);
            else
                return $this->config[$key];
        }
        
        return null;
    }
}
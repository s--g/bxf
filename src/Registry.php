<?php

namespace BxF;

class Registry
{
    protected static RegistryStore $registryStore;
    
    public static function setStore(RegistryStore $value): void
    {
        self::$registryStore = $value;
    }
    
    public static function get(): RegistryStore
    {
        return self::$registryStore;
    }
}
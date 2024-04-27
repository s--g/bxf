<?php
declare(strict_types = 1);

namespace BxF;

use ReflectionClass;
use ReflectionException;

class AttributeParser
{
    protected static array $cache = [];
    
    /**
     * @param string $className
     * @param string|null $attribute
     * @param string|null $metadataKey
     * @return array|mixed Returns array if $metadataKey == null, or mixed otherwise
     * @throws ReflectionException
     */
    public static function get(string $className, ?string $attribute = null, ?string $metadataKey = null): mixed
    {
        $attributes = self::loadCache($className);
        
        if($attribute === null)
            return $attributes;
        
        if(isset($attributes[$attribute]))
        {
            if($metadataKey === null)
                return $attributes[$attribute];
            
            return $attributes[$attribute][$metadataKey] ?? null;
        }
        else
            return [];
    }
    
    /**
     * @param string $className
     * @return array
     * @throws ReflectionException|ReflectionException
     */
    private static function loadCache(string $className): array
    {
        if(!isset(self::$cache[$className]))
        {
            self::$cache[$className] = [];
            $class = new ReflectionClass($className);

            foreach($class->getProperties() as $property)
            {
                $docBlock = $property->getDocComment();
                if(!$docBlock)
                    continue;
                
                preg_match_all('/@((BxF)\\\.*?) (.*)\n/', $docBlock, $matches);
                
                if (!empty($matches) && !empty($matches[1]))
                    self::$cache[$className][$property->getName()] = self::getValues($matches);
                else
                    self::$cache[$className][$property->getName()] = [];
            }
        }
        
        return self::$cache[$className];
    }
    
    /**
     * @param array $matches
     * @return array
     */
    private static function getValues(array $matches): array
    {
        $rtn = [];
        
        for($i = 0; $i < sizeof($matches[1]); $i++)
            eval('$rtn[\''.$matches[1][$i].'\'] = '.$matches[3][$i].";");
        
        return $rtn;
    }
}

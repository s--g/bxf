<?php
declare(strict_types = 1);

namespace BxF;

use ReflectionException;

abstract class Model
{
    use PropertyAccess;
	
    public function validate(): Validator\ValidationResult
    {
        return Validator::validate($this);
    }
    
    public static function mapOne($rows): ?object
    {
        if(empty($rows))
            return null;
        
        return static::mapRow($rows);
    }
    
    public static function map($rows): array
    {
        $rtn = [];
        
        if(empty($rows))
            return $rtn;
        
        foreach($rows as $row)
            $rtn[] = static::mapRow($row);
        
        return $rtn;
    }
    
    /**
     * @param array $filter
     * @return array
     * @throws ReflectionException
     */
    public static function getAttributes(array $filter = []): array
    {
        $attributes = AttributeParser::get(get_called_class());
        
        if(empty($filter))
            return $attributes;
        
        $rtn = [];
        
        foreach($attributes as $name => $metadata)
        {
            $include = true;
            foreach($filter as $filterName => $filterVal)
            {
                if(is_numeric($filterName))
                {
                    if(str_contains($filterVal, '*'))
                    {
                        $include = false;
                        
                        foreach($metadata as $key => $val)
                        {
                            if(str_contains($key, str_replace('*', '', $filterVal)))
                            {
                                $include = true;
                                break;
                            }
                        }
                    }
                    else
                        $include = array_key_exists($filterVal, $metadata);
                }
                else
                {
                    if(array_key_exists($filterName, $metadata))
                    {
                        if(is_array($metadata[$filterName]))
                            $include = in_array($filterVal, $metadata[$filterName]);
                        else
                            $include = $metadata[$filterName] === $filterVal;
                    }
                }
            }
            
            if($include)
                $rtn[$name] = $metadata;
        }
        
        return $rtn;
    }
}

<?php
declare(strict_types = 1);

namespace BxF;

use BxF\Validator\Status;
use BxF\Validator\ValidationResult;
use ReflectionException;

class Validator
{
    /**
     * Validates the given entity
     *
     * @param Model $entity
     * @param string[] $fields
     * @return ValidationResult
     * @throws ReflectionException
     */
    public static function validate(Model $entity, array $fields = []): ValidationResult
    {
        $attributes = $entity::getAttributes();
        $result = new ValidationResult(Status::Success);
        
        foreach($attributes as $name => $metadata)
        {
            if(!empty($fields) && !in_array($name, $fields))
                continue;
            
            $getMethod = self::getMethodFromAttribute($name);
            
            if(isset($metadata['BxF\FriendlyName']))
                $friendlyName = $metadata['BxF\FriendlyName'];
            else
                $friendlyName = $name;
            
            if(isset($metadata['BxF\Mandatory'])
                && $metadata['BxF\Mandatory']
                && ($entity->$getMethod() === null || $entity->$getMethod() === ''))
            {
                $result->addMessage("A value is required for [$friendlyName]");
                continue;
            }
            
            if (!empty($metadata) && $entity->$getMethod() === null)
                continue;
            
            foreach($metadata as $key => $val)
            {
                switch($key)
                {
                    case 'BxF\MinVal':
                        if($entity->$getMethod() < $val)
                            $result->addMessage("Invalid value for [$friendlyName]");
                        
                        break;
                    
                    case 'BxF\MaxLength':
                        if(mb_strlen($entity->$getMethod(), 'UTF-8') > $val)
                            $result->addMessage("[$friendlyName] exceeds the maximum allowable length of $val characters");
                        
                        break;
                    
                    case 'BxF\Enum':
                        if(!in_array($entity->$getMethod(), $metadata['SB\Enum']))
                            $result->addMessage("Invalid value for [$friendlyName]");
                        
                        break;
                }
            }
        }
        
        if($result->getMessages())
            $result->setStatus(Status::Failure);
        
        return $result;
    }
    
    /**
     * @param string $attribute
     * @return string
     */
    protected static function getMethodFromAttribute(string $attribute): string
    {
        return 'get'.ucfirst($attribute);
    }
}

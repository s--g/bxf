<?php
declare(strict_types = 1);

namespace BxF;

abstract class Model
{
    use PropertyAccess;
	
    public function __construct()
    {
    
    }
    
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
}
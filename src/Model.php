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
}
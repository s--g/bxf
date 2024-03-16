<?php

namespace BxF\Db;

use BxF\Db\Result\Code;
use BxF\PropertyAccess;

/**
 * @method Code getCode()
 * @method $this setCode(Code $value)
 */
class Result
{
    use PropertyAccess;
    
    protected Code $code;
    
    public function __construct(Code $code)
    {
        $this->code = $code;
    }
}
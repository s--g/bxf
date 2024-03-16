<?php

namespace BxF\Db;

use BxF\Db\Result\Code;

/**
 * @method Code getCode()
 * @method $this setCode(Code $value)
 */
class Result
{
    protected Code $code;
    
    public function __construct(Code $code)
    {
        $this->code = $code;
    }
}
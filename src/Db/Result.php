<?php

namespace BxF\Db;

use BxF\Db\Result\Code;

class Result
{
    protected Code $code;
    
    public function __construct(Code $code)
    {
        $this->code = $code;
    }
}
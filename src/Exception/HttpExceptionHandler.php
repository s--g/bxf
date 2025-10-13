<?php declare(strict_types = 1);

namespace BxF\Exception;

use BxF\Http\Response\Body;
use BxF\Http\Response\Code;
use BxF\Http\Response\JsonBody;

class HttpExceptionHandler
{
    public function handle(\Exception $ex): Body
    {
        if($ex instanceof NotFound)
            $code = Code::NotFound;
        elseif($ex instanceof InvalidMethod)
            $code = Code::InvalidMethod;
        elseif($ex instanceof InvalidContentType)
            $code = Code::InvalidContentType;
        else
            $code = Code::ServerError;
        
        reg()->getResponse()->setCode($code);
        return new JsonBody($ex->getMessage(), $ex->getTrace());
    }
}
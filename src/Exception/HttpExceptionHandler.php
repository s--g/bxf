<?php declare(strict_types = 1);

namespace BxF\Exception;

use BxF\Http\Response\Code;
use BxF\Http\Response\JsonResponse;

class HttpExceptionHandler
{
    public function handle(\Exception $ex): JsonResponse
    {
        if($ex instanceof NotFound)
            $code = Code::NotFound;
        elseif($ex instanceof InvalidMethod)
            $code = Code::InvalidMethod;
        elseif($ex instanceof InvalidContentType)
            $code = Code::InvalidContentType;
        else
            $code = Code::ServerError;
        
        return new JsonResponse($code, $ex->getMessage(), $ex->getTrace());
    }
}
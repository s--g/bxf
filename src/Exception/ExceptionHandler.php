<?php declare(strict_types = 1);

namespace BxF\Exception;

use BxF\Application;
use BxF\Http\Response\Code;
use BxF\Http\Response\JsonResponse;
use BxF\Plugin\BootstrapPlugin;
use BxF\Registry;

class ExceptionHandler
    implements BootstrapPlugin
{
    public function onBootstrap(Application $application): bool
    {
        Registry::get()->setExceptionHandler($this);
        return true;
    }
    
    public function handle(\Exception $ex): JsonResponse
    {
        // TODO: Conditionally send exception message based on env
        return new JsonResponse(Code::ServerError, $ex->getMessage(), $ex->getTrace());
    }
}
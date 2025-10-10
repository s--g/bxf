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
    
    public static function handle(\Throwable $ex): void
    {
        // TODO: Conditionally send exception message based on env
        (new JsonResponse(Code::ServerError, $ex->getMessage(), $ex->getTrace()))->render();
    }
}
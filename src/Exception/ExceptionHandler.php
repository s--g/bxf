<?php declare(strict_types = 1);

namespace BxF\Exception;

use BxF\Application;
use BxF\Http\Response\Body;
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
    
    public static function handle(\Throwable $ex): Body
    {
        // TODO: Conditionally send exception message based on env
        return (new JsonBody($ex->getMessage(), $ex->getTrace()));
    }
}
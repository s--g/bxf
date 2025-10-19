<?php declare(strict_types = 1);

namespace BxF\Exception;

use BxF\Application;
use BxF\Http\Response\JsonBody;
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
        reg()->getResponse()->setBody(
            new JsonBody(
                get_class($ex).': '.$ex->getMessage().' in '.$ex->getFile().':'.$ex->getLine(),
                $ex->getTrace()
            )
        );
        
        reg()->getApplication()->preResponse();
        echo(reg()->getResponse()->render());
    }
}
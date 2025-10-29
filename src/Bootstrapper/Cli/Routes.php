<?php declare(strict_types = 1);

namespace BxF\Bootstrapper\Cli;

use BxF\Application;
use BxF\Cli\Request;
use BxF\Cli\Router;
use BxF\Exception;
use BxF\Plugin\BootstrapPlugin;

class Routes implements BootstrapPlugin
{
    protected array $routes;
    
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }
    
    /**
     * @param Application $application
     * @return bool
     * @throws Exception
     */
    public function onBootstrap(Application $application): bool
    {
        global $argv;
        $router = new Router;
        
        foreach($this->routes as $route)
            $router->addRoute($route);
        
        if(!isset($argv[1]))
        {
            echo("Please specify a route\n");
            exit(1);
        }
        
        $application
            ->setRouter($router)
            ->setRequest(
                new Request($argv)
            );
        
        return true;
    }
}
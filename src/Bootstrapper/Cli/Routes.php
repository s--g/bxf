<?php
declare(strict_types = 1);

namespace BxF\Bootstrapper\Cli;

use BxF\Application;
use BxF\Bootstrapper\BootstrapperInterface;
use BxF\Cli\Request;
use BxF\Cli\Router;

class Routes implements BootstrapperInterface
{
    protected array $routes;
    
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }
    
    /**
     * @param Application $application
     */
    public function bootstrap(Application $application) : void
    {
        global $argv;
        $router = new Router;
        
        foreach($this->routes as $route)
            $router->addRoute($route);
        
        $config = $application->getConfig();
        
        if(!isset($argv[1]))
        {
            echo("Please specify a route\n");
            exit(1);
        }
        
        $application
            ->setRouter($router)
            ->setRequest(
                (new Request($argv))
            );
    }
}
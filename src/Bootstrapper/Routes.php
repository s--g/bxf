<?php
declare(strict_types = 1);

namespace BxF\Bootstrapper;

use BxF\Router;
use BxF\Request;
use BxF\Application;
use BxF\Http\Method;

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
    public function run(Application $application) : void
    {
        $router = new Router;
        
        foreach($this->routes as $route)
            $router->addRoute($route);
            
        $baseUrl = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        
        $application
            ->setRouter($router)
            ->setRequest(
                (new Request)
                    ->setMethod(Method::fromString($_SERVER['REQUEST_METHOD']))
                    ->setUri($requestUri)
                    ->setBaseUrl($baseUrl)
            );
    }
}
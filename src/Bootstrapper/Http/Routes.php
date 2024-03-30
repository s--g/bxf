<?php
declare(strict_types = 1);

namespace BxF\Bootstrapper\Http;

use BxF\Application;
use BxF\Bootstrapper\BootstrapperInterface;
use BxF\Http\Method;
use BxF\Http\Request;
use BxF\Http\Router;

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
        $router = new Router;
        
        foreach($this->routes as $route)
            $router->addRoute($route);
        
        $config = $application->getConfig();
        $baseUrl = $config->get('base_url');
        if(empty($baseUrl))
            $baseUrl  = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        
        $requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        
        $application
            ->setRouter($router)
            ->setRequest(
                (new Request(
                    Method::fromString($_SERVER['REQUEST_METHOD']),
                    $requestUri,
                    $baseUrl
                ))
            );
    }
}
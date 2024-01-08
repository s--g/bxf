<?php
declare(strict_types = 1);

namespace BxF;

use BxF\Http\Method;
use BxF\Router\Route;
use BxF\Http\InvalidMethodException;
use BxF\Http\InvalidContentTypeException;

/**
 * Class Router
 */
class Router
{
    protected array $routes;
    
    public function __construct()
    {
        $this->routes = [];
    }
    
    /**
     * @param Route $route
     * @return $this
     */
    public function addRoute(Route $route) : Router
    {
        $this->routes[$route->getRoute()] = $route;
        return $this;
    }
    
    /**
     * @throws InvalidContentTypeException
     * @throws InvalidMethodException
     */
    public function routeRequest(Request $request) : void
    {
        Registry::setRequest($request);
        if(array_key_exists($request->getRoute(), $this->routes))
        {
            $route = $this->routes[$request->getRoute()];
            
            if(!in_array(Method::fromString($_SERVER['REQUEST_METHOD']), $route->getMethods()))
                throw new InvalidMethodException($_SERVER['REQUEST_METHOD']);
            
            if(!empty($route->getContentTypes()) &&
               isset($_SERVER['CONTENT_TYPE']) &&
               !in_array($_SERVER['CONTENT_TYPE'], $route->getContentTypes()))
                throw new InvalidContentTypeException;
            
            $controllerName = $route->getController();
            $controller = new $controllerName($request);
            Registry::setController($controller);
            $response = $controller->handle();
            echo($response->render());
            return;
        }
        
        die('404 error');
    }
}
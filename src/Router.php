<?php

namespace BxF;

/**
 * @method array getRoutes()
 * @method $this setRoutes(array $value)
 *
 * @method Route getCurrentRoute()
 * @method $this setCurrentRoute(Route $value)
 */
abstract class Router
{
    use PropertyAccess;
    
    /**
     * @var Route[]
     */
    protected array $routes;
    
    protected ?Route $currentRoute;
    
    /**
     * @param Route $route
     * @return $this
     */
    public function addRoute(Route $route) : Router
    {
        $this->routes[$route->getRoute()] = $route;
        return $this;
    }
    
    public abstract function routeRequest(Request $request);
}
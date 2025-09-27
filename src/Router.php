<?php declare(strict_types = 1);

namespace BxF;

use BxF\Http\Route;

/**
 * @method array getRoutes()
 *
 * @method Route getCurrentRoute()
 * @method $this setCurrentRoute(Route $value)
 */
abstract class Router
{
    use PropertyAccess;
    
    /**
     * The collection of routes, indexed by their first part
     *
     * @var array
     */
    protected array $routesIndexed;
    
    protected ?Route $currentRoute;
    
    /**
     * @param Route[] $routes
     * @throws Exception
     */
    public function __construct(array $routes)
    {
        $this->routesIndexed = [];
        $this->currentRoute = null;
        
        // Build the index
        foreach($routes as $route)
            $this->addRoute($route);
    }
    
    public function setRoutes(array $routes): static
    {
        $this->routesIndexed = [];
        foreach($routes as $route)
            $this->addRoute($route);
        return $this;
    }
    
    /**
     * @param Route $route
     * @return $this
     * @throws Exception
     */
    public function addRoute(Route $route) : Router
    {
        if($part = $route->getRoutePartByIndex(0))
        {
            if(!isset($this->routesIndexed[$part]))
                $this->routesIndexed[$part] = [];
            
            $this->routesIndexed[$part][] = $route;
        }
        else
            throw new Exception("Can't add route [".$route->getRoute()."] because it appears to have no parts");
        
        return $this;
    }
    
    public function listRoutes(): array
    {
        return array_merge(...array_values($this->routesIndexed));
    }
    
    /**
     * Lists routes by their first part
     *
     * @param string $firstPart
     * @return Route[]
     */
    public function listRoutesByFirstPart(string $firstPart): array
    {
        return $this->routesIndexed[$firstPart]??[];
    }
    
    public abstract function routeRequest(Request $request);
}
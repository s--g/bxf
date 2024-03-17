<?php
declare(strict_types = 1);

namespace BxF;

use BxF\Http\Method;
use BxF\Router\Route;
use BxF\Http\InvalidMethodException;
use BxF\Http\InvalidContentTypeException;

/**
 * @method array getRoutes()
 *
 * @method Route getCurrentRoute()
 */
class Router
{
    use PropertyAccess;
    
    /**
     * @var Route[]
     */
    protected array $routes;
    
    protected ?Route $currentRoute;
    
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
        foreach($this->routes as $route)
        {
            $req = $route->match($request);
            if($req === null)
                continue;
            
            Registry::setRequest($req);
            $this->currentRoute = $route;
            
            if(!in_array(Method::fromString($_SERVER['REQUEST_METHOD']), $this->currentRoute->getMethods()))
                throw new InvalidMethodException($_SERVER['REQUEST_METHOD']);
            
            if(!empty($this->currentRoute->getContentTypes()) &&
               isset($_SERVER['CONTENT_TYPE']) &&
               !in_array($_SERVER['CONTENT_TYPE'], $this->currentRoute->getContentTypes()))
                throw new InvalidContentTypeException;
            
            $controllerName = $this->currentRoute->getController();
            $controller = new $controllerName($request);
            Registry::setController($controller);
            $response = $controller->handle();
            echo($response->render());
            return;
        }
        
        die('404 error');
    }
}
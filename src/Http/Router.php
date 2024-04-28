<?php
declare(strict_types = 1);

namespace BxF\Http;

use BxF\Http\Exception\InvalidContentType;
use BxF\Http\Exception\InvalidMethod;
use BxF\Http\Exception\NotFound;
use BxF\Registry;
use BxF\Request;

class Router extends \BxF\Router
{
    public function __construct()
    {
        $this->routes = [];
    }
    
    /**
     * @param Request $request
     * @throws InvalidContentType
     * @throws InvalidMethod
     * @throws NotFound
     */
    public function routeRequest(\BxF\Request $request) : void
    {
        foreach($this->routes as $route)
        {
            $req = $route->match($request);
            if($req === null)
                continue;
            
            Registry::setRequest($req);
            $this->currentRoute = $route;
            
            if(!in_array(Method::fromString($_SERVER['REQUEST_METHOD']), $this->currentRoute->getMethods()))
                throw new InvalidMethod($_SERVER['REQUEST_METHOD']);
            
            if(!empty($this->currentRoute->getContentTypes()) &&
               isset($_SERVER['CONTENT_TYPE']) &&
               !in_array($_SERVER['CONTENT_TYPE'], $this->currentRoute->getContentTypes()))
                throw new InvalidContentType;
            
            $controllerName = $this->currentRoute->getController();
            $controller = new $controllerName($request);
            Registry::setController($controller);
            $response = $controller->handle();
            echo($response->render());
            return;
        }
        
        throw new NotFound;
    }
}
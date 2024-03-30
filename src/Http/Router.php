<?php
declare(strict_types = 1);

namespace BxF\Http;

use BxF\Registry;

class Router extends \BxF\Router
{
    public function __construct()
    {
        $this->routes = [];
    }
    
    /**
     * @throws InvalidContentTypeException
     * @throws InvalidMethodException
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
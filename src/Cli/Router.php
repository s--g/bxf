<?php

namespace BxF\Cli;

use BxF\Registry;
use BxF\Request;


class Router extends \BxF\Router
{
    public function routeRequest(Request $request)
    {
        foreach($this->routes as $route)
        {
            $req = $route->match($request);
            if($req === null)
                continue;
            
            Registry::get()->setRequest($req);
            $this->currentRoute = $route;
            
            $controllerName = $this->currentRoute->getController();
            $controller = new $controllerName($request);
            Registry::get()->setController($controller);
            $response = $controller->handle();
            echo($response->render());
            return;
        }
        
        echo("404 error\n");
        exit(1);
    }
}
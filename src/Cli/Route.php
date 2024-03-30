<?php

namespace BxF\Cli;

use BxF\PropertyAccess;

/**
 * Class Route
 *
 * @method string getRoute()
 *
 * @method $this setRoute(string $value)
 * @method $this setController(string|null $controller)
 */
class Route extends \BxF\Route
{
    use PropertyAccess;
    
    protected string $route;
    
    protected ?string $controller;
    
    public function __construct(string $route)
    {
        $this->route = $route;
        $this->controller = null;
    }
    
    public function match(\BxF\Request $request): ?Request
    {
        if($request->getArgs()[1] == $this->route)
            return $request;
        
        return null;
    }
}
<?php

namespace BxF\Router;

use BxF\Http\Method;
use BxF\PropertyAccess;
use BxF\Controller;
use BxF\Request;

/**
 * Class Route
 *
 * @package Router
 *
 * @method string getRoute()
 *
 * @method $this setRoute(string $value)
 * @method $this setController(string|null $controller)
 */
class Route
{
    use PropertyAccess;
    
    protected string $route;
    
    protected ?string $controller;
    
    protected array $routeParts;
    
    /**
     * @var string[]
     */
    protected array $contentTypes;
    
    /**
     * @var Method[]
     */
    protected array $methods;
    
    public function __construct(string $route)
    {
        $this->route = $route;
        $this->controller = null;
        $this->contentTypes = [];
        $this->methods = [];
        $this->routeParts = explode('/', $route);
    }
    
    public function acceptContentType(string $type) : Route
    {
        $this->contentTypes[] = $type;
        return $this;
    }
    
    public function acceptMethods(array $methods) : Route
    {
        $this->methods = $methods;
        return $this;
    }
    
    public function getUrlParameterPositionNumber(string $name): ?int
    {
        foreach($this->routeParts as $key => $value)
        {
            if($name == $value)
                return $key;
        }
        
        return null;
    }
    
    public function match(Request $request): ?Request
    {
        $match = true;
        $pathVariables = [];
        foreach($this->routeParts as $index => $routePart)
        {
            $requestUrlPart = $request->getUrlPart($index);
            if(str_starts_with($routePart, ':'))
            {
                if(empty($requestUrlPart))
                    $match = false;
                else
                    $pathVariables[ltrim($routePart, ':')] = $requestUrlPart;
            }
            elseif($requestUrlPart != $routePart)
                $match = false;
        }
        
        if(!$match)
            return null;
        
        return $request->setPathVariables($pathVariables);
    }
}
<?php

namespace BxF\Router;

use BxF\Http\Method;
use BxF\PropertyAccess;
use BxF\Controller;

/**
 * Class Route
 *
 * @package Router
 *
 * @method $this setRoute(string $value)
 * @method $this setController(string|null $controller)
 */
class Route
{
    use PropertyAccess;
    
    protected string $route;
    
    protected ?string $controller;
    
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
}
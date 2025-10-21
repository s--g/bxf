<?php declare(strict_types = 1);

namespace BxF\Http;

use BxF\Controller;
use BxF\Exception\ConfigurationException;
use BxF\Http\Exception\InvalidMethod;
use BxF\PropertyAccess;
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
class Route extends \BxF\Route
{
    use PropertyAccess;
    
    protected string $route;
    
    protected array $handlers;
    
    protected array $routeParts;
    
    /**
     * @var string[]
     */
    protected array $contentTypes;
    
    /**
     * @throws ConfigurationException
     */
    public function __construct(string $route)
    {
        $this->route = $route;
        $this->handlers = [];
        $this->contentTypes = [];
        
        if(!str_starts_with($route, '/'))
            throw new ConfigurationException("Route [{$route}] must start with a forward slash");
        
        $this->routeParts = explode('/', ltrim($route, '/'));
        
    }
    
    public function acceptContentType(string $type): Route
    {
        $this->contentTypes[] = $type;
        return $this;
    }
    
    public function getRoutePartByIndex(int $index): ?string
    {
        return $this->routeParts[$index]??null;
    }
    
    /**
     * Returns the index of the given route part
     *
     * @param string $name
     * @return int|null
     */
    public function getUrlParameterIndex(string $name): ?int
    {
        return array_find_key($this->routeParts, fn($value) => $name == $value);
    }
    
    /**
     * @param \BxF\Http\Request $request
     * @return bool
     */
    public function matches(Request $request): bool
    {
        foreach($this->routeParts as $index => $routePart)
        {
            $requestUrlPart = $request->getUrlPart($index);
            
            if(str_starts_with($routePart, ':'))
            {
                if(empty($requestUrlPart))
                    return false;
            }
            elseif($requestUrlPart !== $routePart)
                return false;
        }
        
        return true;
    }
    
    public function supportsMethod(Method $method): bool
    {
        return array_key_exists($method->value, $this->handlers);
    }
    
    public function getHandlerForMethod(Method $method): ?string
    {
        return $this->handlers[$method->value] ?? null;
    }
    
    public function getAcceptedMethods(): array
    {
        return array_map(fn($method) => Method::from($method), array_keys($this->handlers));
    }
    
    public function extractPathVariables(Request $request): array
    {
        $variables = [];
        
        foreach ($this->routeParts as $index => $routePart)
        {
            if (str_starts_with($routePart, ':'))
                $variables[ltrim($routePart, ':')] = $request->getUrlPart($index);
        }
        
        return $variables;
    }
    
    public function addHandler(Method $method, string $controller): static
    {
        $this->handlers[$method->value] = $controller;
        return $this;
    }
}
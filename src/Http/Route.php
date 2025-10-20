<?php declare(strict_types = 1);

namespace BxF\Http;

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
 *
 * @method Method[] getAcceptedMethods()
 * @method $this setAcceptedMethods(array $value)
 */
class Route extends \BxF\Route
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
    protected array $acceptedMethods;
    
    public function __construct(string $route)
    {
        $this->route = $route;
        $this->controller = null;
        $this->contentTypes = [];
        $this->acceptedMethods = [];
        
        if(!str_starts_with($route, '/'))
            throw new ConfigurationException("Route [{$route}] must start with a forward slash");
        
        $this->routeParts = explode('/', ltrim($route, '/'));
        
    }
    
    public function addAcceptedMethod(Method $method): static
    {
        $this->acceptedMethods[] = $method;
        return $this;
    }
    
    public function acceptContentType(string $type) : Route
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
        foreach($this->routeParts as $key => $value)
        {
            if($name == $value)
                return $key;
        }
        
        return null;
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
        
        if(!in_array($request->getMethod(), $this->getAcceptedMethods()))
            throw new InvalidMethod($request->getUri().' does not support method '.$request->getMethod()->value);
        
        return true;
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
}
<?php declare(strict_types = 1);

namespace BxF\Http;

use BxF\Application;
use BxF\Exception;
use BxF\Http\Exception\InvalidContentType;
use BxF\Http\Exception\InvalidMethod;
use BxF\Http\Exception\NotFound;
use BxF\Http\Request as HttpRequest;
use BxF\Plugin\BootstrapPlugin;
use BxF\Registry;
use BxF\Request;

class Router
    extends \BxF\Router
    implements BootstrapPlugin
{
    /**
     * @param HttpRequest $request
     * @throws InvalidContentType
     * @throws InvalidMethod
     * @throws NotFound
     */
    public function routeRequest(Request $request): void
    {
        $this->currentRoute = $this->getRouteMatchingRequest($request);
        
        if($this->currentRoute === null)
            throw new NotFound;
        
        $request->setPathVariables($this->currentRoute->extractPathVariables($request));

        $method = Method::from($_SERVER['REQUEST_METHOD']);
        if(!$this->currentRoute->supportsMethod($method))
            throw new InvalidMethod($_SERVER['REQUEST_METHOD']);
        
        if(!empty($this->currentRoute->getContentTypes()) &&
           isset($_SERVER['CONTENT_TYPE']) &&
           !in_array($_SERVER['CONTENT_TYPE'], $this->currentRoute->getContentTypes()))
            throw new InvalidContentType;
        
        $controllerName = $this->currentRoute->getHandlerForMethod($method);
        $controller = new $controllerName($request);
        reg()->setController($controller);
        
        $app = reg()->getApplication();
        $app->getResponse()->setBody($controller->handle());
        $app->preResponse();
        echo($app->getResponse()->render());
    }
    
    /**
     * @param HttpRequest $request
     * @return Route|null
     */
    public function getRouteMatchingRequest(HttpRequest $request): ?Route
    {
        return array_find(
            $this->listRoutesByFirstPart($request->getUrlPart(0)),
            fn($route) => $route->matches($request)
        );
    }
    
    /**
     * @param Application $application
     * @return bool
     */
    public function onBootstrap(Application $application): bool
    {
        $requestPath = $_SERVER['REQUEST_URI'];
        $basePath = reg()->getApplication()->getBasePath();
        
        if(str_starts_with($requestPath, $basePath))
            $requestPath =  substr($requestPath, strlen($basePath));
        
        $requestPath = parse_url($requestPath, PHP_URL_PATH);
        
        $application
            ->setRouter($this)
            ->setRequest(
                new \BxF\Http\Request(
                    Method::from($_SERVER['REQUEST_METHOD']),
                    $requestPath
                )->setQueryString($_GET)
            );
        
        Registry::get()->setRequest($application->getRequest());
        return true;
    }
}
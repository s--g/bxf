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

        if(!in_array(Method::from($_SERVER['REQUEST_METHOD']), $this->currentRoute->getAcceptedMethods()))
            throw new InvalidMethod($_SERVER['REQUEST_METHOD']);
        
        if(!empty($this->currentRoute->getContentTypes()) &&
           isset($_SERVER['CONTENT_TYPE']) &&
           !in_array($_SERVER['CONTENT_TYPE'], $this->currentRoute->getContentTypes()))
            throw new InvalidContentType;
        
        $controllerName = $this->currentRoute->getController();
        $controller = new $controllerName($request);
        Registry::get()->setController($controller);
        $response = $controller->handle();
        Registry::get()->getApplication()->preResponse();
        echo($response->render());
    }
    
    /**
     * @param HttpRequest $request
     * @return Route|null
     */
    public function getRouteMatchingRequest(HttpRequest $request): ?Route
    {
        foreach($this->listRoutesByFirstPart($request->getUrlPart(0)) as $route)
        {
            if($route->matches($request))
                return $route;
        }
        
        return null;
    }
    
    /**
     * @param Application $application
     * @throws Exception
     */
    public function onBootstrap(Application $application): bool
    {
        foreach($this->listRoutes() as $route)
            $this->addRoute($route);
        
        $config = $application->getConfig();
        $baseUrl = $config->get('base_url');
        if(empty($baseUrl))
            $baseUrl  = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $application
            ->setRouter($this)
            ->setRequest(
                (new \BxF\Http\Request(
                    Method::from($_SERVER['REQUEST_METHOD']),
                    $requestUri,
                    $baseUrl
                ))->setQueryString($_GET)
            );
        
        Registry::get()->setRequest($application->getRequest());
        return true;
    }
}
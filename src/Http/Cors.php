<?php declare(strict_types = 1);

namespace BxF\Http;

use BxF\Application;
use BxF\Exception;
use BxF\Exception\ConfigurationException;
use BxF\Plugin\BootstrapPlugin;
use BxF\Plugin\PreRenderPlugin;
use BxF\Registry;

class Cors
    implements BootstrapPlugin, PreRenderPlugin
{
    /**
     * @throws ConfigurationException
     * @throws Exception
     */
    public function onBootstrap(Application $application): bool
    {
        $router = $application->getRouter();
        
        /**
         * @var Route $route
         */
        foreach($router->listRoutes() as $route)
        {
            $router->addRoute(
                new Route($route->getRoute())
                    ->addHandler(Method::OPTIONS, '\BxF\Http\CorsResponse')
            );
        }
        
        return true;
    }
    
    public function onPreRender(): bool
    {
        if(headers_sent())
            return false;
        
        $router = Registry::get()->getApplication()->getRouter();
        
        if(empty($router))
            return false;
        
        if($acceptedMethods = $router->getCurrentRoute()?->getAcceptedMethods())
            header('Access-Control-Allow-Methods: '.implode(',', array_map(fn($method) => $method->value, $acceptedMethods)));
        
        if($this->getAllowedOrigin())
            header('Access-Control-Allow-Origin: '.$this->getAllowedOrigin());
        
        // TODO: Add these conditionally with configured values
        header('Access-Control-Allow-Headers: Content-Type,Authorization');
        header('Access-Control-Allow-Credentials: true');
        
        return true;
    }
    
    /**
     * Returns the contents of the "Origin" request header, unless overridden in config
     *
     * @return string|null
     */
    protected function getAllowedOrigin(): ?string
    {
        // $override = config.get('allowOriginOverride');
        //if($override)
        //    return $override;
        
        return $_SERVER['HTTP_ORIGIN'] ?? null;
    }
}
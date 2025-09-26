<?php

namespace BxF\Http;

use BxF\Plugin\PreRenderPlugin;
use BxF\Registry;

class Cors
    implements PreRenderPlugin
{
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
<?php
declare(strict_types = 1);

namespace BxF;

/**
 * @method string getBasePath()
 */
class Application
{
    use PropertyAccess;
    
    protected array $config;
    
    /**
     * @var Router
     */
    protected Router $router;
    
    /**
     * @var string
     */
    protected string $basePath;
    
    protected array $layoutPaths;
    
    /**
     * @var Request
     */
    protected Request $request;
    
    public function __construct()
    {
        $this->layoutPaths = [];
        $this->basePath = '';
        Registry::setApplication($this);
    }
    
    public function setConfig(string $configFilename, string $environment): static
    {
        $config = require($configFilename);
        $this->config = $config[$environment];
        
        if(isset($this->config['php']) && !empty($this->config['php']))
        {
            foreach($this->config['php'] as $key => $val)
                ini_set($key, $val);
        }
        
        return $this;
    }
    
    public function setBasePath(string $path): static
    {
        set_include_path(
            implode(
                PATH_SEPARATOR, [
                    get_include_path(),
                    $path,
                ]
            )
        );
        
        return $this;
    }
    
    public function addLayoutPath(string $path)
    {
        $this->layoutPaths[] = $path;
        return $this;
    }
    
    public function bootstrap(array $bootstrappers)
    {
        foreach($bootstrappers as $bootstrapper)
            $bootstrapper->run($this);
        
        $this->router->routeRequest($this->request);
    }
}

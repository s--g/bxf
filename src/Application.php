<?php
declare(strict_types = 1);

namespace BxF;

/**
 * @method string getBasePath()
 *
 * @method array getConfig()
 */
class Application
{
    use PropertyAccess;
    
    protected array $config;
    
    protected Router $router;
    
    protected string $basePath;
    
    protected array $layoutPaths;
    
    /**
     * @var Request
     */
    protected Request $request;
    
    public function __construct(string $configDir)
    {
        $this->layoutPaths = [];
        $this->basePath = '';
        $this->configDir = null;
        
        foreach(glob($configDir.'/*.php') as $configFilename)
        {
            $this->config = require($configFilename);
            
            if(isset($this->config['php']) && !empty($this->config['php']))
            {
                foreach($this->config['php'] as $key => $val)
                    ini_set($key, $val);
            }
        }
        
        Registry::setApplication($this);
    }
    
    public function setConfig(string $configFilename, string $environment): static
    {

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

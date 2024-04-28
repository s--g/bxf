<?php
declare(strict_types = 1);

namespace BxF;

use BxF\Http\Exception\NotFound;
use BxF\Http\Exception\InvalidContentType;
use BxF\Http\Exception\InvalidMethod;

/**
 * @method string getBasePath()
 *
 * @method array getConfig()
 *
 * @method Router getRouter()
 */
class Application
{
    use PropertyAccess;
    
    protected Config $config;
    
    protected Router $router;
    
    protected string $basePath;
    
    protected array $layoutPaths;
    
    /**
     * @var Request
     */
    protected Request $request;
    
    public function __construct(string $configDir)
    {
        $this->basePath = '';
        $this->layoutPaths = [];
        $mergedConfig = [];
        
        foreach(glob($configDir.'/*.php') as $configFilename)
        {
            $config = require($configFilename);
            
            if(isset($config['php']) && !empty($config['php']))
            {
                foreach($config['php'] as $key => $val)
                    ini_set($key, $val);
            }
            
            $mergedConfig = array_merge($mergedConfig, $config);
        }
        
        $this->config = new Config($mergedConfig);
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
            $bootstrapper->bootstrap($this);
        
        try
        {
            $this->router->routeRequest($this->request);
        }
        catch(InvalidMethod $ex)
        {
            // TODO: Return HTTP 405
        }
        catch(NotFound $ex)
        {
            // TODO: Return HTTP 404
        }
        catch(InvalidContentType $ex)
        {
            // TODO: Return HTTP 415
        }
    }
}

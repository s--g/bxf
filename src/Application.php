<?php declare(strict_types = 1);

namespace BxF;

use BxF\Exception\ExceptionHandler;
use BxF\Plugin\BootstrapPlugin;
use BxF\Plugin\PreRenderPlugin;
use BxF\Plugin\PreResponse;

/**
 * @method string getBasePath()
 *
 * @method Config getConfig()
 *
 * @method Router getRouter()
 *
 * @method Request getRequest()
 * @method $this setRequest(Request $value)
 *
 * @method array getHeaders()
 * @method $this setHeaders(array $value)
 *
 * @method array getPlugins()
 * @method $this setPlugins(array $value)
 */
class Application
{
    use PropertyAccess;
    
    protected Config $config;
    
    protected Router $router;
    
    protected string $basePath;
    
    protected array $layoutPaths;
    
    protected bool $corsEnabled;
    
    protected array $responseHeaders;
    
    protected Registry $registry;
    
    /**
     * @var array Plugin[]
     */
    protected array $plugins;
    
    /**
     * @var Request
     */
    protected Request $request;
    
    public function __construct(string $configDir, RegistryStore $registryStore)
    {
        $this->basePath = '';
        $this->layoutPaths = [];
        $mergedConfig = [];
        $this->enableCors = false;
        $this->headers = [];
        $this->bootstrappers = [];
        $this->plugins = [];
        
        Registry::setStore($registryStore);
        
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
        Registry::get()
            ->setApplication($this)
            ->setConfig($this->config);
    }
    
    public function addResponseHeader(string $header): static
    {
        $this->responseHeaders[] = $header;
        return $this;
    }
    
    public function getBaseUrl()
    {
        return 'https://'.$_SERVER['HTTP_HOST'];
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
    
    /**
     * Does the following:
     * * Sends CORS headers
     * * Enables OPTIONS method implicitly for all routes
     *
     * @param bool $value
     * @return $this
     */
    public function enableCors(bool $value): self
    {
        $this->corsEnabled = $value;
        return $this;
    }
    
    public function isCorsEnabled(): bool
    {
        return $this->corsEnabled;
    }
    
    public function run(): void
    {
        Registry::get()->setExceptionHandler(new ExceptionHandler);
        
        try
        {
            foreach($this->plugins as $plugin)
            {
                if($plugin instanceof BootstrapPlugin)
                    $plugin->onBootstrap($this);
            }
            
            $this->router->routeRequest($this->request);
        }
        catch(\Exception $ex)
        {
            echo(Registry::get()->getExceptionHandler()->handle($ex)->render());
        }
    }
    
    public function preResponse(): void
    {
        foreach($this->plugins as $plugin)
        {
            if($plugin instanceof PreRenderPlugin)
                $plugin->onPreRender();
        }
    }
}

<?php declare(strict_types = 1);

namespace BxF;

use BxF\Plugin\BootstrapPlugin;
use BxF\Plugin\PreRenderPlugin;
use BxF\Plugin\PreResponse;
use BxF\Http\Response;

/**
 * @method string getBasePath()
 *
 * @method Config getConfig()
 *
 * @method Router getRouter()
 *
 * @method array getPlugins()
 * @method $this setPlugins(array $value)
 *
 * @method Request getRequest()
 * @method $this setRequest(Request $value)
 *
 * @method Response getResponse()
 * @method $this setResponse(Response $value)
 */
class Application
{
    use PropertyAccess;
    
    protected Config $config;
    
    protected Router $router;
    
    protected string $basePath;
    
    protected array $layoutPaths;
    
    protected bool $corsEnabled;
    
    protected Registry $registry;
    
    /**
     * @var array Plugin[]
     */
    protected array $plugins;
    
    /**
     * @var Request
     */
    protected Request $request;
    
    /**
     * @var Response
     */
    protected Response $response;
    
    public function __construct(string $configDir, RegistryStore $registryStore)
    {
        $this->basePath = '';
        $this->layoutPaths = [];
        $mergedConfig = [];
        $this->corsEnabled = false;
        $this->plugins = [];
        $this->response = new Response;
        
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
        
        reg()
            ->setApplication($this)
            ->setConfig($this->config);
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
    
    public function bootstrap(): void
    {
        foreach($this->plugins as $plugin)
        {
            if($plugin instanceof BootstrapPlugin)
                $plugin->onBootstrap($this);
        }
    }
    
    public function run(): void
    {
        $this->bootstrap();
        $this->router->routeRequest($this->request);
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

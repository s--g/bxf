<?php
declare(strict_types = 1);

namespace BxF\Document\Html;

use BxF\Registry;
use BxF\PropertyAccess;

/**
 * Class View
 *
 * @method array getParams()
 * @method $this setParams(array $value)
 */
class View
{
    use PropertyAccess;
    
    // const DEFAULT_LAYOUT = 'main.phtml';
    
    protected string $viewScript;
    
    // protected string $frameScript;
    
    /**
     * @var string
     */
    //protected string $layoutScript;
    
    /**
     * @var array
     */
    protected array $params;
    
    /**
     * @var array
     */
    protected $css;
    
    /**
     * @var array
     */
    protected $script;
    
    public function __construct(string $viewScript, array $params = [])
    {
    //    $this->layoutScript = self::DEFAULT_LAYOUT;
    //    $this->frameScript = $frameScript;
    
        //$this->viewScript = Registry::get()->getController()->getDirectory().DIRECTORY_SEPARATOR.$viewScript;
        $this->viewScript = $viewScript;
        
        if(isset($params['params']))
            $this->params = $params['params'];
        else
            $this->params = [];
        
        if(isset($params['css']))
            $this->css = $params['css'];
        else
            $this->css = [];
        
        if(isset($params['script']))
            $this->script = $params['script'];
        else
            $this->script = [];
    }
    
    public function getCss()
    {
        return $this->css;
    }
    
    public function getCssLinks()
    {
        $rtn = '';
        foreach($this->css as $css)
        {
            $rtn .= '<link rel="stylesheet" type="text/css" href="/deploy/public/css/views/'.$css.'">'."\n";
        }
        
        return $rtn;
    }
    
    public function getScriptLinks()
    {
        $rtn = '';
        foreach($this->script as $s)
        {
            $rtn .= '<script type="text/javascript" src="'.$this->baseUrl().'/script/views/'.$s.'"></script>'."\n";
        }
        
        return $rtn;
    }
 
    public function render()
    {
        ob_start();
        include $this->viewScript;
        return ob_get_clean();
    }
    
    /*
    public function __call($name, $arguments)
    {
        $name = '\View\Helper\\'.ucfirst($name);
        return (new $name($arguments))();
    }
    
    public function renderView() : void
    {
        include(BASE_PATH.'/views/_frames/'.$this->frameScript);
    } */
    
    public function getParam($name)
    {
        if(isset($this->params[$name]))
            return $this->params[$name];
        
        throw new Exception('Invalid parameter ['.$name.']');
    }
    
    public function setParam($key, $value): static
    {
        $this->params[$key] = $value;
        return $this;
    }
}
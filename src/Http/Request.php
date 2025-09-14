<?php
declare(strict_types = 1);

namespace BxF\Http;

use BxF\PropertyAccess;
use BxF\Session;

/**
 * @method Method getMethod()
 * @method $this serMethod(Method $value)
 *
 * @method string getUri()
 *
 * @method array getUrlParts()
 * @method $this setUrlParts(array $value)
 *
 * @method string|null getBody()
 * @method $this setBody(string|null $value)
 *
 * @method string|null getBaseUrl()
 * @method $this setBaseUrl(string|null $value)
 *
 * @method array getQueryString()
 * @method $this setQueryString(array $value)
 *
 * @method array getPathVariables()
 * @method $this setPathVariables(array $value)
 *
 * @method Session getSession()
 * @method $this setSession(Session $value)
 */
class Request extends \BxF\Request
{
    use PropertyAccess;
    
    protected Method $method;
    
    protected string $uri;
    
    protected array $urlParts;
    
    protected ?string $body;
    
    protected ?string $baseUrl;
    
    protected array $queryString;
    
    protected array $pathVariables;
    
    protected Session $session;
    
    public function __construct(Method $method, string $uri, string $baseUrl = '')
    {
        $this->method = $method;
        $this->baseUrl = $baseUrl;
        $this->urlParts = [];
        $this->setUri($uri);
        $this->body = null;
        $this->queryString = [];
        $this->pathVariables = [];
    }
    
    public function isPost()
    {
        return $this->method == Method::POST;
    }

    public function setUri(string $value): self
    {
        $this->uri = $value;
        $this->urlParts = explode('/', $this->getRoute());
        return $this;
    }
    
    public function getQuery($name): ?string
    {
        return $this->queryString[$name] ?? null;
    }
    
    public function getPathVariable($name): ?string
    {
        if(!isset($this->pathVariables[$name]))
            return null;
        
        return $this->pathVariables[$name];
    }
    
    public function getPostData()
    {
        return json_decode(file_get_contents("php://input"), true);
    }
    
    public function getPost(string $name): ?string
    {
        $postData = $this->getPostData();
        return $postData[$name]??null;
    }
    
    public function getRoute()
    {
        if(str_starts_with($this->uri, $this->baseUrl))
            return str_replace($this->baseUrl, '', $this->uri);
        
        return $this->uri;
    }
    
    public function getUrlPart(int $index): ?string
    {
        if(isset($this->urlParts[$index]))
            return $this->urlParts[$index];
        
        return null;
    }
}
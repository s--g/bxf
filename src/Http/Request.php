<?php declare(strict_types = 1);

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
    
    protected array $queryString;
    
    protected array $pathVariables;
    
    protected ?Session $session;
    
    public function __construct(Method $method, string $uri)
    {
        $this->method = $method;
        $this->urlParts = [];
        $this->setUri($uri);
        $this->body = null;
        $this->queryString = [];
        $this->pathVariables = [];
        $this->session = null;
    }
    
    public function isPost(): bool
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
    
    public function getHeaders(): array
    {
        return getallheaders();
    }
    
    public function getHeader(string $name): ?string
    {
        $headers = $this->getHeaders();
        return $headers[$name]??null;
    }
    
    public function getCookies(): array
    {
        return $_COOKIE;
    }
    
    public function getCookie(string $name): ?string
    {
        $cookies = $this->getCookies();
        return $cookies[$name]??null;
    }
    
    public function getBody(): string
    {
        return json_decode(file_get_contents("php://input"), true);
    }
    
    public function getPost(string $name): ?string
    {
        $body = $this->getBody();
        return $body[$name]??null;
    }
    
    public function getRoute(): string
    {
        return ltrim($this->uri, '/');
    }
    
    public function getUrlPart(int $index): ?string
    {
        if(isset($this->urlParts[$index]))
            return $this->urlParts[$index];
        
        return null;
    }
}
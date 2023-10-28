<?php
declare(strict_types = 1);

namespace BxF;

use BxF\Http\Method;

class Request
{
    use PropertyAccess;
    
    protected Method $method;
    
    protected string $uri;
    
    protected string $body;
    
    protected string $baseUrl;
    
    protected Session $session;
    
    public function isPost()
    {
        return $this->method == Method::POST;
    }
    
    public function getPostData(): array
    {
        return $_POST;
    }
}
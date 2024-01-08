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
}
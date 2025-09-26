<?php declare(strict_types = 1);

namespace BxF;

use BxF\Db\Adapter;
use BxF\Exception\ExceptionHandler;

class RegistryStore
{
    protected ?Application $application = null;
    protected ?Request $request = null;
    protected ?Controller $controller = null;
    protected ?ExceptionHandler $exceptionHandler = null;
    protected ?Config $config = null;
    protected ?Adapter $db = null;
    protected ?User $user = null;
    
    public function getApplication(): ?Application
    {
        return $this->application;
    }
    
    public function setApplication(Application $value): static
    {
        $this->application = $value;
        return $this;
    }
    
    public function getRequest(): ?Request
    {
        return $this->request;
    }
    
    public function setRequest(Request $value): static
    {
        $this->request = $value;
        return $this;
    }
    
    public function getController(): ?Controller
    {
        return $this->controller;
    }
    
    public function setController(Controller $value): static
    {
        $this->controller = $value;
        return $this;
    }
    
    public function getExceptionHandler(): ?ExceptionHandler
    {
        return $this->exceptionHandler;
    }
    
    public function setExceptionHandler(ExceptionHandler $value): static
    {
        $this->exceptionHandler = $value;
        return $this;
    }
    
    public function getConfig(): ?Config
    {
        return $this->config;
    }
    
    public function setConfig(Config $value): static
    {
        $this->config = $value;
        return $this;
    }
    
    public function getDb(): ?Adapter
    {
        return $this->db;
    }
    
    public  function setDb(Adapter $value): static
    {
        $this->db = $value;
        return $this;
    }
    
    public function getUser(): ?User
    {
        return $this->user;
    }
    
    public function setUser(User $value): static
    {
        $this->user = $value;
        return $this;
    }
    
    public static function get(): static
    {
        return new self;
    }
}
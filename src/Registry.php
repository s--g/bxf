<?php declare(strict_types = 1);

namespace BxF;

use BxF\Db\Adapter;
use BxF\Exception\ExceptionHandler;

class Registry
{
    use PropertyAccess;
    
    protected static ?Application $application = null;
    protected static ?Request $request = null;
    protected static ?Controller $controller = null;
    protected static ?ExceptionHandler $exceptionHandler = null;
    protected static ?Config $config = null;
    protected static ?Adapter $db = null;
    protected static ?User $user = null;
    
    public static function getApplication(): ?Application
    {
        return self::$application;
    }
    
    public static function setApplication(Application $value): void
    {
        self::$application = $value;
    }
    
    public static function getRequest(): ?Request
    {
        return self::$request;
    }
    
    public static function setRequest(Request $value): void
    {
        self::$request = $value;
    }
    
    public static function getController(): ?Controller
    {
        return self::$controller;
    }
    
    public static function setController(Controller $value): void
    {
        self::$controller = $value;
    }
    
    public static function getExceptionHandler(): ?ExceptionHandler
    {
        return self::$exceptionHandler;
    }
    
    public static function setExceptionHandler(ExceptionHandler $value): void
    {
        self::$exceptionHandler = $value;
    }
    
    public static function getConfig(): ?Config
    {
        return self::$config;
    }
    
    public static function setConfig(Config $value): void
    {
        self::$config = $value;
    }
    
    public static function getDb(): ?Adapter
    {
        return self::$db;
    }
    
    public static function setDb(Adapter $value): void
    {
        self::$db = $value;
    }
    
    public static function getUser(): ?User
    {
        return self::$user;
    }
    
    public static function setUser(User $value): void
    {
        self::$user = $value;
    }
}
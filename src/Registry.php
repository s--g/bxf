<?php
declare(strict_types = 1);

namespace BxF;

class Registry
{
    use PropertyAccess;
    
    protected static Application $application;
    protected static Request $request;
    protected static Controller $controller;
    
    public static function setApplication(Application $application)
    {
        self::$application = $application;
    }
    
    public static function getApplication(): Application
    {
        return self::$application;
    }
    
    public static function setRequest(Request $request)
    {
        self::$request = $request;
    }
    
    public static function getRequest(): Request
    {
        return self::$request;
    }
    
    public static function setController(Controller $controller)
    {
        self::$controller = $controller;
    }
    
    public static function getController(): Controller
    {
        return self::$controller;
    }
    
}
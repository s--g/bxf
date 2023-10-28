<?php
declare(strict_types = 1);

namespace BxF;

use BxF\Http\Response;

/**
 * @method Request getRequest()
 * @method $this setRequest(Request $request)
 */
abstract class Controller
{
    use PropertyAccess;
    
    /**
     * @var Request
     */
    protected Request $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function getDirectory(): string
    {
        return dirname((new \ReflectionClass(static::class))->getFileName());
    }
    
    public abstract function handle(): Response;
}
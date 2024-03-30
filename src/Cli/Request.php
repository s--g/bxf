<?php
declare(strict_types = 1);

namespace BxF\Cli;

use BxF\PropertyAccess;

/**
 * @method array getArgs()
 * @method $this setArgs(array $value)
 */
class Request extends \BxF\Request
{
    use PropertyAccess;
    
    protected array $args;
    
    public function __construct(array $argv)
    {
        $this->args = $argv;
    }
}
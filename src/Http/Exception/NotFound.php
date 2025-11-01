<?php declare(strict_types = 1);

namespace BxF\Http\Exception;

use BxF\Http\Exception;

class NotFound extends Exception
{
    public function __construct(string $message = "Not found", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
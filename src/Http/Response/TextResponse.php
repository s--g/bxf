<?php

namespace BxF\Http\Response;

use BxF\Http\Response;
use BxF\PropertyAccess;

/**
 * @method string getContent()
 * @method $this setContent(string $value)
 */
class TextResponse
    extends Response
    implements ResponseInterface
{
    use PropertyAccess;
    
    protected ?string $content;
    
    public function __construct(string $text)
    {
        $this->content = $text;
    }
    
    public function render(): string
    {
        return $this->content;
    }
}
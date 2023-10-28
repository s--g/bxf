<?php
declare(strict_types = 1);

namespace BxF\Document;

use BxF\PropertyAccess;

/**
 * @method string getFileName()
 * @method $this setFileName(string $value)
 *
 * @method string getContent()
 * @method $this setContent(string $value)
 */
class Template
{
    use PropertyAccess;
    
    protected string $fileName;
    
    protected string $content;
    
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        ob_start();
        include $fileName;
        $this->content = ob_get_clean();
    }
    
    public function replaceContent(string $placeholder, string $content): self
    {
        $this->content = str_replace($placeholder, $content, $this->content);
        return $this;
    }
}
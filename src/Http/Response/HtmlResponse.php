<?php

namespace BxF\Http\Response;

use BxF\Document\Html\View;
use BxF\Document\Html\Layout;
use BxF\Document\Html\Document;
use BxF\Http\Response;
use BxF\PropertyAccess;

/**
 * @method Document getDocument()
 * @method $this setDocument(Document $value)
 *
 * @method Layout getLayout(Layout $value)
 * @method $this setLayout(Layout $value)
 *
 * @method View getView()
 * @method $this setView(View $value)
 */
class HtmlResponse
    extends Response
    implements ResponseInterface
{
    use PropertyAccess;
    
    protected ?Document $document;
    protected ?Layout $layout;
    protected ?View $view;
    
    public function __construct(View $view)
    {
        $this->document = null;
        $this->layout = null;
        $this->view = $view;
    }
    
    public function render(): string
    {
        $loader = new \Twig\Loader\ArrayLoader([
            'index' => $this->view->render()
        ]);
        $twig = new \Twig\Environment($loader);
        $viewString = $twig->render('index', $this->view->getParams());
        
        if($this->layout !== null)
        {
            $this->layout->replaceContent('<content />', $viewString);
            if($this->document !== null)
                return $this->document->replaceContent('<content />', $this->layout->getContent())->getContent();
            
            return $this->layout->getContent();
        }
        else if($this->document !== null)
            return $this->document->replaceContent('<content />', $viewString)->getContent();
        
        return $viewString;
    }
}
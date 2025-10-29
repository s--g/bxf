<?php declare(strict_types = 1);

namespace BxF\Http\Response;

use BxF\Exception;
use BxF\PropertyAccess;

/**
 * Class JsonBody
 *
 * @package Http\Response
 *
 * @method string|null getMessage()
 * @method $this setMessage(string|null $value)
 *
 * @method array|null getData()
 * @method $this setData(array|null $value)
 */
class JsonBody
    extends Body
    implements ResponseInterface
{
    use PropertyAccess;
    
    /**
     * @var ?string
     */
    protected ?string $message;
    
    /**
     * @var ?array
     */
    protected ?array $data;
    
    /**
     * JsonBody constructor.
     */
    public function __construct()
    {
        $this->message = null;
        $this->data = null;
    }
    
    public function render(): void
    {
        reg()->getApplication()->getResponse()->addHeader('Content-Type: application/json');
        
        $fields = [];
        if(!empty($this->message))
            $fields['message'] = $this->message;
        
        if(!empty($this->data))
            $fields['data'] = $this->data;
        
        if(empty($fields))
            return;
        
        $body = json_encode($fields);
        
        if($body === false)
            throw new Exception('Unable to encode response');
        
        echo($body);
    }
}
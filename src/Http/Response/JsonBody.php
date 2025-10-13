<?php declare(strict_types = 1);

namespace BxF\Http\Response;

use BxF\Exception;
use BxF\PropertyAccess;

/**
 * Class JsonBody
 *
 * @package Http\Response
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
     *
     * @param string|null $message
     * @param array|null $data
     */
    public function __construct(?string $message = null, ?array $data = null)
    {
        $this->message = $message;
        $this->data = $data;
    }
    
    public function render(): void
    {
        reg()->getApplication()->getResponse()->addHeader('Content-Type: application/json');
        
        $response = json_encode([
            'message' => $this->message,
            'data' => $this->data
        ]);
        
        if($response === false)
            throw new Exception('Unable to encode response');
        
        echo($response);
    }
}
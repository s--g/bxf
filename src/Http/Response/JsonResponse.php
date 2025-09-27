<?php

namespace BxF\Http\Response;

use BxF\Http\Response;
use BxF\Http\Response\JsonResponse\Status;
use BxF\PropertyAccess;

/**
 * Class JsonResponse
 *
 * @package Http\Response
 */
class JsonResponse
	extends Response
	implements ResponseInterface
{
	use PropertyAccess;
	
	/**
	 * @var Code
	 */
	protected Code $code;
	
	/**
	 * @var ?string
	 */
	protected ?string $message;
	
	/**
	 * @var ?array
	 */
	protected ?array $data;
	
	/**
	 * JsonResponse constructor.
	 *
	 * @param Code $code
	 * @param string|null $message
	 * @param array|null $data
	 */
	public function __construct(Code $code, ?string $message = null, ?array $data = null)
	{
		$this->code = $code;
		$this->message = $message;
		$this->data = $data;
	}
	
	public function render() : void
	{
        if(!headers_sent())
        {
            header('Content-Type: application/json');
            http_response_code($this->code->value);
        }
  
		echo(
			json_encode([
				'message' => $this->message,
				'data' => $this->data
			])
		);
	}
}
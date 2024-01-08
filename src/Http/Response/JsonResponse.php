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
	
	const STATUS_SUCCESS = 'success';
	const STATUS_ERROR = 'error';
	
	/**
	 * @var Status
	 */
	protected Status $status;
	
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
	 * @param Status $status
	 * @param string|null $message
	 * @param array|null $data
	 */
	public function __construct(Status $status, ?string $message, ?array $data)
	{
		$this->status = $status;
		$this->message = $message;
		$this->data = $data;
	}
	
	public function render() : void
	{
		header('Content-Type: application/json');
		
		echo(
			json_encode([
				'status' => $this->status->value,
				'message' => $this->message,
				'data' => $this->data
			])
		);
	}
}
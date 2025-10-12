<?php declare(strict_types = 1);

namespace BxF\Log;

use BxF\Event\EventInterface;
use BxF\Model;

/**
 * @method string getMessage()
 * @method $this setMessage(string $value)
 *
 * @method Priority getPriority()
 * @method $this setPriority(Priority $value)
 *
 * @method array getDetail()
 * @method $this setDetail(array $value)
 */
class Item
    extends Model
    implements EventInterface
{
    protected string $message;
    protected Priority $priority;
    protected array $detail;
    
    public function __construct(string $message, Priority $priority = Priority::Info, array $detail = [])
    {
        $this->message = $message;
        $this->priority = $priority;
        $this->detail = $detail;
    }
}

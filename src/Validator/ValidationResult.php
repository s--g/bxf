<?php
declare(strict_types = 1);

namespace BxF\Validator;

use BxF\PropertyAccess;

/**
 * @method Status getStatus()
 * @method $this setStatus(Status $value)
 *
 * @method string[] getMessages()
 * @method $this setMessages(string[] $value)
 */
class ValidationResult
{
    use PropertyAccess;
    
    protected Status $status;
    
    /**
     * @var string[]
     */
    protected array $messages;
    
    public function __construct(Status $status, ?array $messages = [])
    {
        $this->status = $status;
        $this->messages = $messages;
    }
    
    public function addMessage(string $message): self
    {
        $this->messages[] = $message;
        return $this;
    }
    
    public function getMessagesAsString(): string
    {
        return implode(',', $this->messages);
    }
    
    public function isValid(): bool
    {
        return $this->status === Status::Success;
    }
}
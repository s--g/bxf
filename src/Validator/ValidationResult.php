<?php

namespace BxF\Validator;

class ValidationResult
{
    protected Status $status;
    
    protected array $messages;
    
    public function __construct(Status $status, array $messages = [])
    {
        $this->status = $status;
        $this->messages = $messages;
    }
    
    public function getMessagesAsString(): string
    {
        return implode(',', $this->messages);
    }
    
    public function isValid()
    {
        return $this->status === Status::Success;
    }
}
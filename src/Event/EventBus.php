<?php declare(strict_types = 1);

namespace BxF\Event;

use BxF\Event;
use BxF\EventInterface;

class EventBus
{
    /**
     * @var EventHandler[]
     */
    protected array $handlers;
    
    /**
     * @param EventHandler ...$handler
     */
    public function __construct(EventHandler ...$handler)
    {
        $this->handlers = $handler;
    }
    
    public function subscribe(EventHandler $handler): static
    {
        $this->handlers = $handler;
        return $this;
    }
    
    public function raiseEvent(Event $event, EventInterface ...$object): static
    {
        foreach($this->handlers as $handler)
            $handler->handleEvent($event, ...$object);
        
        return $this;
    }
}
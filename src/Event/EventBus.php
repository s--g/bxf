<?php declare(strict_types = 1);

namespace BxF\Event;

use BxF\Event;
use BxF\EventInterface;
use BxF\EventHandler;

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
        $this->listeners = $handler;
    }
    
    public function subscribe(EventHandler $handler): static
    {
        $this->listeners = $handler;
        return $this;
    }
    
    public function raiseEvent(Event $event, EventInterface ...$object): static
    {
        foreach($this->handlers as $handler)
            $handler->handleEvent($event, ...$object);
        
        return $this;
    }
}
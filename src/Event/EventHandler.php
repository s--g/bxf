<?php

namespace BxF\Event;

interface EventHandler
{
    public function handleEvent(Event $event, EventInterface ...$object): void;
}
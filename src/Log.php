<?php

namespace BxF;

abstract class Log
{
    public abstract function write(string $message, Priority $priority = Priority::Info, string $detail): static;
}
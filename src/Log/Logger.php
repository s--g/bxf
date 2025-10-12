<?php

namespace BxF\Log;

abstract class Logger
{
    public abstract function write(Item $item): static;
}
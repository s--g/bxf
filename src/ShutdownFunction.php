<?php

namespace BxF;

interface ShutdownFunction
{
    public function shutdown(): bool;
}
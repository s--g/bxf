<?php

namespace BxF;

abstract class Route
{
    public abstract function match(Request $request): ?Request;
}
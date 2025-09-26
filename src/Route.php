<?php

namespace BxF;

abstract class Route
{
    public abstract function matches(Request $request): bool;
}
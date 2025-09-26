<?php declare(strict_types = 1);

namespace BxF\Plugin;

use BxF\Plugin;

interface PreRenderPlugin
    extends Plugin
{
    public function onPreRender(): bool;
}
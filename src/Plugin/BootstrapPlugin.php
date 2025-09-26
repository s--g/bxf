<?php declare(strict_types = 1);

namespace BxF\Plugin;

use BxF\Application;
use BxF\Plugin;

interface BootstrapPlugin
    extends Plugin
{
	public function onBootstrap(Application $application): bool;
}
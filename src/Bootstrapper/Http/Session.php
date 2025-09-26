<?php
declare(strict_types = 1);

namespace BxF\Bootstrapper\Http;

use Application;
use BxF\Plugin\BootstrapPlugin;

/**
 * Class Session
 *
 * @package Bootstrapper
 */
class Session implements BootstrapPlugin
{
	/**
	 * @param Application $application
	 */
	public function run(Application $application) : void
	{
		if(isset($_COOKIE['sid']))
			$session = \Session::retrieve($_COOKIE['sid']);
		else
			$session = new \Session;
		
		$application->getRequest()->setSession($session);
	}
    
    public function onBootstrap(\BxF\Application $application): bool
    {
        // TODO: Implement onBootstrap() method.
    }
}
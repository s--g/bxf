<?php
declare(strict_types = 1);

namespace BxF\Bootstrapper\Http;

use Application;
use Bootstrapper\BootstrapperInterface;

/**
 * Class Session
 *
 * @package Bootstrapper
 */
class Session implements BootstrapperInterface
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
}
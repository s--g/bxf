<?php
declare(strict_types = 1);

namespace BxF\Bootstrapper;

use BxF\Application;

/**
 * Interface BootstrapperInterface
 *
 * @package Bootstrapper
 */
interface BootstrapperInterface
{
	/**
	 * @param Application $application
	 * @return mixed
	 */
	public function bootstrap(Application $application);
}
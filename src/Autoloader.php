<?php
declare(strict_types = 1);

class Autoloader
{
	public static function register()
	{
		spl_autoload_register(function($class)
		{
			$file = realpath(
				BASE_PATH.'/application/'.
				str_replace('\\', '/', $class).'.php'
			);
			
			if(!empty($file) && file_exists($file))
				require_once($file);
		});
	}
}
<?php
class AutoLoader
{

	public static function load($className)
	{
		// need to make this way smarter
		$file = dirname(__FILE__) .'/includes/' . $className . '.php';
		
		if(file_exists($file))
		{
			include $file;
		}
	}
	
	public static function fauxThreadLoad($className)
	{
		$file = dirname(__FILE__) . '/externals/fauxThread/src/' . $className . '.php';
		
		if(is_file($file))
		{
			include $file;
		}
	}
}
?>
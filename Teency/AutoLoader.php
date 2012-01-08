<?php

/**
 * AutoLoader class.
 * 
 * functions for autoloading files required for teensy
 *
 * @author Matthew Shafer <matt@niftystopwatch.com>
 */
class AutoLoader
{
	
	/**
	 * load function.
	 * 
	 * auto loads all of the teensy files
	 *
	 * @param className
	 */
	public static function load($className)
	{
		// need to make this way smarter
		$file = dirname(__FILE__) .'/includes/' . $className . '.php';
		
		if(file_exists($file))
		{
			include $file;
		}
	}
	
	/**
	 * fauxThreadLoad function.
	 * 
	 * loads the files required for fauxThread
	 *
	 * @param className
	 */
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
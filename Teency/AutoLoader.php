<?php
class AutoLoader
{

	public static function load($className)
	{
		// need to make this way smarter
		include 'includes/' . $className . '.php';
	}
}
?>
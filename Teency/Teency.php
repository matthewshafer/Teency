<?php

require_once("AutoLoader.php");

spl_autoload_register('AutoLoader::load');


set_error_handler('ErrorHandler::storeError', E_ALL);
assert_options(ASSERT_CALLBACK, 'ErrorHandler::assertCallback');
assert_options(ASSERT_WARNING, 0);

//trigger_error("test123", E_USER_ERROR);

//print_r(ErrorHandler::$errors)

class Teency
{
	public static function teencyVersion()
	{
		// version numbers are YYYYMMDD encoded, they only change on releases
		// for alpha/beta releases the version number is that of the previous release
		return 20110212;
	}
}

?>
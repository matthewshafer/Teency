<?php

require_once("AutoLoader.php");

spl_autoload_register('AutoLoader::load');


set_error_handler('ErrorHandler::storeError', E_ALL);

//trigger_error("test123", E_USER_ERROR);

//print_r(ErrorHandler::$errors)

?>
<?php
class ErrorHandler
{
	public static $errors = array();
	
	public static function storeError($errorNumber, $errorString, $errorFile, $errorLine, $errorContext)
	{
		$tmp = array('number' => $errorNumber, 'string' => $errorString, 'file' => $errorFile, 'line' => $errorLine, 'context' => $errorContext);
		self::$errors[] = $tmp;
	}
	
	public static function haveErrors()
	{
		$ret = false;
		
		if(isset(self::$errors[0]))
		{
			$ret = true;
		}
		
		return $ret;
	}
	
	public static function getErrors()
	{
		$ct = count(self::$errors);
		$str = "";
		
		for($i = 0; $i < $ct; $i++)
		{
			$str .= self::$errors[$i]['string'] . ' ';
		}
		
		return $str;
	}
	
	public static function clearErrors()
	{
		self::$errors = array();
	}
	
}
?>
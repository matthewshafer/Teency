<?php
class ErrorHandler
{
	public static $errors = array();
	
	public static function storeError($errorNumber, $errorString)
	{
		$tmp = array('number' => $errorNumber, 'string' => $errorString);
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
	
	public static function assertCallback($fileName, $lineNumber, $expression)
	{
		$str = "Assert failed in the file $fileName on line number $lineNumber.";
		
		// only add on the expression if it is not null (so if the assert is not something like true === true
		if($expression != null)
		{
			$str .= " Expression was $expression.";
		}
		
		self::storeError(null, $str);
	}
	
}
?>
<?php

/**
 * ErrorHandler class.
 * 
 * Handles various errors that php can throw
 *
 * @author Matthew Shafer <matt@niftystopwatch.com>
 */
class ErrorHandler
{
	public static $errors = array();
	
	/**
	 * storeError function.
	 * 
	 * Stores the error number and the string related to the error into the $errors array
	 *
	 * @param $errorNumber
	 * @param $errorString
	 */
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

	public static function errorCount()
	{
		return count(self::$errors);
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
		
		$str .= "\n";
		
		self::storeError(null, $str);
	}
	
	public static function errorHandlerCallback($errorNumber, $errorString, $errorFile, $errorLine)
	{
		$str = $errorString . " in " . $errorFile . " on line " . $errorLine . ".\n";
		
		self::storeError($errorNumber, $str);
	}

	public static function equalityBacktrace($info, $backtrace)
	{
		$info = $info . ": " . self::equalityBacktraceLineNumber($backtrace). "\n";

		self::storeError(null, $info);
	}

	private static function backtraceFormatter($backtrace)
	{
		return var_export($backtrace, true);
	}

	private static function equalityBacktraceLineNumber($backtrace)
	{
		return isset($backtrace[0]['line']) ? $backtrace[0]['line'] : null;
	}
	
}
?>
<?php

class TestSuiteData
{
	private static $passed = 0;
	private static $failed = 0;
	
	public static function testPassed()
	{
		self::$passed++;
	}
	
	public static function testFailed()
	{
		self::$failed++;
	}
	
	public static function totalTests()
	{
		return self::$passed + self::$failed;
	}
	
	public static function totalPassed()
	{
		return self::$passed;
	}
	
	public static function totalFailed()
	{
		return self::$failed;
	}


}
?>
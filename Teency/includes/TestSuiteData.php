<?php

class TestSuiteData
{
	private static $passed = 0;
	private static $failed = 0;
	private static $skipped = 0;
	
	public static function testPassed()
	{
		self::$passed++;
	}
	
	public static function testFailed()
	{
		self::$failed++;
	}

	public static function testSkipped()
	{
		self::$skipped++;
	}
	
	public static function totalTests()
	{
		return self::$passed + self::$failed + self::$skipped;
	}
	
	public static function totalPassed()
	{
		return self::$passed;
	}
	
	public static function totalFailed()
	{
		return self::$failed;
	}

	public static function totalSkipped()
	{
		return self::$skipped;
	}


}
?>
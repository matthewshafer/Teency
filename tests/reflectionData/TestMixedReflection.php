<?php

class TestMixedReflection
{
	public static $test123 = 123;
	public $test1 = 1234;
	private $test2 = 42;
	private static $test3 = 12;
	private $test4;
	private static $test5;
	private $test6;
	
	
	public function __construct($test1, $test2)
	{
		$this->test4 = $test1;
		self::$test5 = $test2;
	}
	
	
	public static function testFun()
	{
		printf("yay the static reflection worked\n");
	}
	
	public function testFun2()
	{
		return 42;
	}
	
	private function testFun3()
	{
		return 15;
	}
	
	private static function testFun4()
	{
		return 22;
	}
	
	public function doubleIt($num)
	{
		return $num * 2;
	}
	
	public static function tripleIt($num)
	{
		return $num * 3;
	}
	
	private function plusTwo($num)
	{
		return $num + 2;
	}
	
	private static function plusTen($num)
	{
		return $num + 10;
	}
	
	private function multiplyTwoNumbers($num1, $num2)
	{
		return $num1 * $num2;
	}

}
?>
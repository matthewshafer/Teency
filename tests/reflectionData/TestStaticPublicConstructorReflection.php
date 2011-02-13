<?php

class TestStaticPublicConstructorReflection
{
	public static $test123;
	
	
	public function __construct($test)
	{
		self::$test123 = $test;
	}
	
	
	public static function test()
	{
		printf("yay the static reflection worked\n");
	}

}
?>
<?php

class TestStaticPrivateReflection
{
	private static $test123 = 123;
	
	public function __construct()
	{
	
	}
	
	private static function test()
	{
		printf("yay the static reflection worked\n");
	}

}
?>
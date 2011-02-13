<?php

class TestConstructorWithArgsReflection
{
	public $test123;
	private $test2;
	private $test3 = 12;
	private $testMe;
	
	
	public function __construct($test, $test1234, $test4 = null)
	{
		$this->test123 = $test;
		$this->test2 = $test1234;
		$this->testMe = $test4;
	}
	
	
	public function test()
	{
		printf("yay the static reflection worked\n");
	}

}
?>
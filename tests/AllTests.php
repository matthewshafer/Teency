<?php
require_once('../Teency/Teency.php');

class AllTests extends TestSuite
{
	public function tests()
	{
		require_once('AssertTest.php');
		$this->load('AssertTest');
		
		require_once('ReflectionTest.php');
		$this->load('ReflectionTest');
	}
}

$run = new AllTests();
$run->tests();

?>
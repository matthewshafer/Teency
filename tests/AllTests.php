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
		
		require_once('FakeObjectTest.php');
		$this->load('FakeObjectTest');
		
		require_once('LongRunningTest.php');
		$this->load('LongRunningTest');
	}
}

$run = new AllTests();
$run->tests();

?>
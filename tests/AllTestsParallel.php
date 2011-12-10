<?php
require_once('../Teency/Teency.php');

class AllTests extends TestSuite
{
	public function tests()
	{
		$this->runParallelTests(true, 20);
		require_once('LongRunningTest.php');
		$this->load('LongRunningTest');
		require_once('AssertTest.php');
		$this->load('AssertTest');
		//$this->drainSocket();
		require_once('ReflectionTest.php');
		$this->load('ReflectionTest');
		//$this->drainSocket();
		require_once('FakeObjectTest.php');
		$this->load('FakeObjectTest');
		//$this->drainSocket();
		
		//$this->drainSocket();
		//$this->outputResults();
	}
}

$run = new AllTests();
$run->tests();

?>
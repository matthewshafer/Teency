<?php
require_once('../Teency/Teency.php');

class AllTests extends TestSuite
{
	public function tests()
	{
		require_once('AssertTest.php');
		$this->load('AssertTest', $runInParallel = true);
		
		require_once('ReflectionTest.php');
		$this->load('ReflectionTest', $runInParallel = true);
		
		require_once('FakeObjectTest.php');
		$this->load('FakeObjectTest', $runInParallel = true);
		
		//$this->drainSocket();
		$this->outputResults();
	}
}

$run = new AllTests();
$run->tests();

?>
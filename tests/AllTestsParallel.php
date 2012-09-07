<?php
require_once('Teency/Teency.php');

class AllTests extends TestSuite
{
	public function tests()
	{
		$this->runParallelTests(true, 20);

		require_once('LongRunningTest.php');
		$this->load('LongRunningTest');

		require_once('EqualityTest.php');
		$this->load('EqualityTest');
		
		require_once('AssertTest.php');
		$this->load('AssertTest');
		
		require_once('ReflectionTest.php');
		$this->load('ReflectionTest');
		
		require_once('FakeObjectTest.php');
		$this->load('FakeObjectTest');
		
	}
}

$run = new AllTests();
$run->tests();

?>
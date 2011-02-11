<?php

class AssertTest extends UnitTest
{

	public function runOnce()
	{
		
	}

	public function testAssertTrue()
	{
		assert(true === true);
		$fake = new FakeObject();
		$fake->addMethod('test', 1);
		assert($fake->test() === 1);
	}
	
	public function testAssertFalse()
	{
		assert(false === false);
	}
	
	public function testAssertOneEqOne()
	{
		assert(1 === 1);
	}

}
?>
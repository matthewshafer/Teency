<?php

class FakeObjectTest extends UnitTest
{

	public function testFakeObjectNullVariable()
	{
		$fake = new FakeObject();
		
		$fake->addVariable('test1', null);
		
		assert($fake->test1 === null);
	}
	
	public function testFakeObjectNullVariableUpdate()
	{
		$fake = new FakeObject();
		
		$fake->test123 = null;
		
		assert($fake->test123 === null);
		
		$fake->test123 = "hello";
		
		assert($fake->test123 === "hello");
	}
	
	public function testFakeObjectMethods()
	{
		$fake = new FakeObject();
		
		$fake->addMethod("testMethod1", array(10, 12, 42));
		$fake->addMethod("testMethod2", "hello");
		$fake->addMethod("testMethod3", 1234);
		$fake->addMethod("testMethod4", true);
		$fake->addMethod("testMethod5", null);
		
		assert($fake->testMethod1() === array(10, 12, 42));
		assert($fake->testMethod2() === "hello");
		assert($fake->testMethod3() === 1234);
		assert($fake->testMethod4() === true);
		assert($fake->testMethod5() === null);
	}
	
	public function testFakeObjectParametersThatDoNothing()
	{
		$fake = new FakeObject();
		
		$fake->addMethod("test1234", "hello");
		
		assert($fake->test1234("test", 1, true) === "hello");
	}
	
	public function testFakeObjectMixVariablesMethods()
	{
		$fake = new FakeObject();
		
		$fake->addMethod("testMethod1", true);
		$fake->testVar1 = true;
		$fake->addVariable('testVar2', "why hello there");
		$fake->addMethod("testMethod2", 1234);
		
		assert($fake->testMethod1() === true);
		assert($fake->testVar1 === true);
		assert($fake->testVar1 === $fake->testMethod1());
		assert($fake->testVar2 === "why hello there");
		assert($fake->testMethod2() === 1234);
		
		$fake->testVar1 = false;
		
		assert($fake->testVar1 === false);
		
		// testing addVariable on an already created variable since it should just call __set() which will change the return value of the variable
		$fake->addVariable('testVar1', 1234);
		
		assert($fake->testVar1 === 1234);
	}



}

?>
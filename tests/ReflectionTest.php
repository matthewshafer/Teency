<?php

require_once('reflectionData/TestPublicReflection.php');
require_once('reflectionData/TestStaticPublicReflection.php');

class ReflectionTest extends UnitTest
{

	public function runOnce()
	{
		
	}

	public function testPublicReflection()
	{
		$refClass = new ReflectClass('TestPublicReflection', null);
		
		$testPublicReflection = $refClass->getReflection();
		
		$testPublicReflection->test();
		
		assert($testPublicReflection->test123 === 123);
		
		$testPublicReflection->test123 = 1234567890;
		
		assert($testPublicReflection->test123 === 1234567890);
		
	}
	
	public function testPrivateReflection()
	{
		// i have yet to implement this so i just made the test fail
		assert('/* not implemented yet */');
	}
	
	public function testStaticPrivateReflection()
	{
		// i have yet ot implement this so i just made the test fail
		assert('/* not implemented yet */');
	}
	
	public function testStaticPublicReflection()
	{
		$refClass = new ReflectClass('TestStaticPublicReflection', null);
		
		$testStatic = $refClass->getReflection();
		
		$testStatic->test();
		
		assert($testStatic->test123 === 123);
		
		$testStatic->test123 = 1234567890;
		
		assert($testStatic->test123 === 1234567890);
	}
	
	public function testStaticPublicConstructor()
	{
		assert('/* not implemented yet */');
	}
	
	public function testMixedReflection()
	{
		assert('/* not implemented yet */');
	}
	
	public function testStaticNoConstructor()
	{
		assert('/* not implemented yet */');
	}

}
?>
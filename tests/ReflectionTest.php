<?php

require_once('reflectionData/TestPublicReflection.php');
require_once('reflectionData/TestStaticPublicReflection.php');
require_once('reflectionData/TestPrivateReflection.php');
require_once('reflectionData/TestStaticPrivateReflection.php');
require_once('reflectionData/TestStaticPublicConstructorReflection.php');
require_once('reflectionData/TestMixedReflection.php');
require_once('reflectionData/TestStaticNoConstructorReflection.php');
require_once('reflectionData/TestNoConstructorReflection.php');
require_once('reflectionData/TestConstructorWithArgsReflection.php');
require_once('reflectionData/TestNotRunningConstructor.php');

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
		
		$refClass = new ReflectClass('TestPrivateReflection', null);
		
		$test = $refClass->getReflection();
		
		$test->test();
		
		$test->test2();
		
		assert($test->test123 === 123);
		
		$test->test123 = 1234567890;
		
		assert($test->test123 = 1234567890);
	}
	
	public function testStaticPrivateReflection()
	{
		$refClass = new ReflectClass('TestStaticPrivateReflection', null);
		
		$test = $refClass->getReflection();
		
		$test->test();
		
		assert($test->test123 === 123);
		
		$test->test123 = 1234567890;
		
		assert($test->test123 === 1234567890);
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
	
	public function testStaticPublicConstructorReflection()
	{
		$refClass = new ReflectClass('TestStaticPublicConstructorReflection', array(123));
		
		$test = $refClass->getReflection();
		
		$test->test();
		
		assert($test->test123 === 123);
		
		$test->test123 = 1234567890;
		
		assert($test->test123 === 1234567890);
	}
	
	public function testMixedReflection()
	{
		$refClass = new ReflectClass('TestMixedReflection', array(555, 999));
		
		$test = $refClass->getReflection();
		
		
		
		// mess with variables here
		assert($test->test123 === 123);
		assert($test-> test1 === 1234);
		assert($test->test2 === 42);
		assert($test->test3 === 12);
		assert($test->test4 === 555);
		assert($test->test5 === 999);
		// returns null because it isn't set
		assert($test->test6 === null);
		
		$test->test6 = "str";
		
		assert($test->test6 === "str");
		
		
		$test->testFun();
		
		assert($test->testFun2() === 42);
		
		assert($test->testFun3() === 15);
		
		assert($test->testFun4() === 22);
		
		assert($test->doubleIt(5) === 10);
		
		assert($test->tripleIt(10) === 30);
		
		// testing a method's return value being used as the variable for another method
		assert($test->tripleIt($test->doubleIt(10)) === 60);
		
		assert($test->plusTwo(2) === 4);
		
		assert($test->plusTen(15) === 25);
		
		assert($test->multiplyTwoNumbers(5, 6) === 30);
	}
	
	public function testStaticNoConstructor()
	{
		$refClass = new ReflectClass('TestStaticNoConstructorReflection', null);
		
		$test = $refClass->getReflection();
		
		$test->test();
		
		assert($test->test123 === 123);
		
		$test->test123 = 1234567890;
		
		assert($test->test123 === 1234567890);
	}
	
	public function testNoConstructor()
	{
		$refClass = new ReflectClass('TestNoConstructorReflection', null);
		
		$test = $refClass->getReflection();
		
		$test->test();
		
		assert($test->test2() === null);
		
		assert($test->test123 === 123);
		
		$test->test123 = 1234567890;
		
		assert($test->test123 === 1234567890);
	}
	
	public function testConstructorWithArgs()
	{
		$refClass = new ReflectClass('TestConstructorWithArgsReflection', array(444, 555));
		
		$test = $refClass->getReflection();
		
		$test->test();
		
		assert($test->test123 === 444);
		
		$test->test123 = 1234567890;
		
		assert($test->test123 === 1234567890);
		
		assert($test->test2 === 555);
		
		assert($test->test3 === 12);
		
		assert($test->testMe === null);
		
		
		
		$refClass = new ReflectClass('TestConstructorWithArgsReflection', array(777, 888, 999));
		
		$test = $refClass->getReflection();
		
		$test->test();
		
		assert($test->test123 === 777);
		
		$test->test123 = 1234567890;
		
		assert($test->test123 === 1234567890);
		
		assert($test->test2 === 888);
		
		assert($test->test3 === 12);
		
		assert($test->testMe === 999);
	}

	public function testNotRunningConstructor()
	{
		$refClass = new ReflectClass('TestNotRunningConstructor', null);
		
		// will not work until php 5.4
		$test = $refClass->getReflection(false);
		echo $test->test;

		assert($test->test === 1);
		assert($test->test2 === "test");
		
	}

}
?>
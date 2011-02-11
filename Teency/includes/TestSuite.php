<?php
class TestSuite
{
	private $loadedTest = null;
	private $testMethods = array();
	
	
	public function load($class)
	{
		$this->testMethods = array();
		$this->loadedTest = new $class();
		
		$getMethods = get_class_methods($this->loadedTest);
		
		$ct = count($getMethods);
		
		for($i = 0; $i < $ct; $i++)
		{
			if(substr_compare($getMethods[$i], 'test', 0, 3) === 0)
			{
				$this->testMethods[] = $getMethods[$i];
			}
		}
		
		$ct = count($this->testMethods);
		
		for($i = 0; $i < $ct; $i++)
		{
			$this->runTest($this->testMethods[$i]);
		}
	}
	
	private function runTest($methodName)
	{
		// need setup and tear down methods
		// need to add reasons why tests failed
		try
		{
			$this->loadedTest->setUpTest();
			$this->loadedTest->$methodName();
		}
		catch(Exception $e)
		{
			if($this->loadedTest->expectedException() === true && get_class($e) === $this->loadedTest->expectedExceptionName())
			{

				printf("%s...passed\n", $methodName);
				TestSuiteData::testPassed();
			}
			else if($this->loadedTest->expectedException() === true && get_class($e) != $this->loadedTest->expectedExceptionName())
			{
				// need to rewrite the error so it sounds better
				printf("%s...failed: Test did not throw expected exception: %s %s\n", $methodName, $this->loadedTest->expectedExceptionName(), ErrorHandler::getErrors());
				TestSuiteData::testFailed();
			}
			// might combine the else if and else statements
			else
			{
				printf("%s...failed: Test threw an exception and we weren't expecting one: %s.\nOther Errors: %s\n", $methodName, $e->getMessage(), ErrorHandler::getErrors());
				TestSuiteData::testFailed();
			}
		}
		
		if($this->loadedTest->expectedException() === false && !ErrorHandler::haveErrors())
		{
			printf("%s...passed\n", $methodName);
			TestSuiteData::testPassed();
		}
		else
		{
			printf("%s...failed: %s\n", $methodName, ErrorHandler::getErrors());
			TestSuiteData::testFailed();
		}
		
		$this->loadedTest->tearDownTest();
		$this->loadedTest->internalCleanupAfterEachTest();
		ErrorHandler::clearErrors();
	}
	
	public function __destruct()
	{
		printf("Total Tests: %d\nTests Passed: %d\nTests Failed: %d\n", TestSuiteData::totalTests(), TestSuiteData::totalPassed(), TestSuiteData::totalFailed());
	}
	
}
?>
<?php

class ParallelRunner implements fauxThreadRunner
{
	
	private $loadedTest;
	private $testMethod;
	private $socket;
	
	public function __construct($loadedTest, $testMethod, $socketWrite)
	{
		$this->loadedTest = $loadedTest;
		$this->testMethod = $testMethod;
		$this->socket = $socketWrite;
	}
	
	public function run()
	{
		//socket_close($this->socket[0]);
		$this->runMultiTest($this->testMethod);
		// closing the socket
		//socket_close($this->socket[1]);
		exit(0);
	}
	
	private function runMultiTest($methodName)
	{
		$testResultArray = array();
		$alreadyCounted = false;
		
		try
		{
			$this->loadedTest->setUpTest();
			$this->loadedTest->$methodName();
		}
		catch(Exception $e)
		{
			if($this->loadedTest->expectedException() === true && get_class($e) === $this->loadedTest->expectedExceptionName())
			{

				$testResultArray['passOrFailStr'] = sprintf("%s...passed", $methodName);
				$testResultArray['pass'] = true; 
				$alreadyCounted = true;
			}
			else if($this->loadedTest->expectedException() === true && get_class($e) != $this->loadedTest->expectedExceptionName())
			{
				// need to rewrite the error so it sounds better
				$testResultArray['passOrFailStr'] = sprintf("%s...failed: Test did not throw expected exception: %s %s", $methodName, $this->loadedTest->expectedExceptionName(), ErrorHandler::getErrors());
				$testResultArray['pass'] = false;
				$alreadyCounted = true;
			}
			// might combine the else if and else statements
			else
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...failed: Test threw an exception and we weren't expecting one: %s.  Other Errors: %s", $methodName, $e->getMessage(), ErrorHandler::getErrors());
				$testResultArray['pass'] = false;
				$alreadyCounted = true;
			}
		}
		
		// if the test hasn't already been counted, so if there wasn't an exception
		if(!$alreadyCounted)
		{
			if($this->loadedTest->expectedException() === false && !ErrorHandler::haveErrors())
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...passed", $methodName);
				$testResultArray['pass'] = true; 
			}
			else
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...failed: %s", $methodName, ErrorHandler::getErrors());
				$testResultArray['pass'] = false;
			}
		}
		
		$this->loadedTest->tearDownTest();
		$this->loadedTest->internalCleanupAfterEachTest();
		ErrorHandler::clearErrors();
		
		$serial = serialize($testResultArray) . "\n";
		//var_dump($serial);
		
		socket_write($this->socket[1], $serial, strlen($serial));
	}
}

?>
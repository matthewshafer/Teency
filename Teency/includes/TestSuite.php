<?php

class TestSuite
{
	private $loadedTest = null;
	private $testMethods = array();
	private $socket = null;
	private $fauxPool = null;
	private $totalTests = 0;
	private $parallelTests = false;
	private $numberOfParallelTests = 2;
	private $startTime = null;
	
	
	public function runParallelTests($enable = true, $numberInParallel = 2)
	{
		$this->parallelTests = $enable;
		$this->numberOfParallelTests =  $numberInParallel;
	}
	
	public function load($class)
	{
		$this->testMethods = array();
		$this->loadedTest = new $class();
		$sockets = array();
		
		if($this->startTime === null)
		{
			$this->startTime = microtime(true);
		}
		
		// building the threadPool and sockets
		if($this->parallelTests === true && $this->fauxPool === null)
		{
			// needs a try catch around this
			$this->fauxPool = new fauxThreadPool($this->numberOfParallelTests);
			
			if(!socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $this->socket))
			{
				die(socket_strerror(socket_last_error()));
			}
			
			// setting the reader to non-blocking so we don't stall out when reading data
			socket_set_nonblock($this->socket[0]);
			//socket_set_nonblock($this->socket[1]);
		}
		
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
		
		if($this->loadedTest->minTeencyVersion() <= Teency::teencyVersion())
		{
			for($i = 0; $i < $ct; $i++)
			{
				if($this->parallelTests === false)
				{
					$this->runTest($this->testMethods[$i]);
					$this->totalTests++;
				}
				else
				{
					$parallelRunner = new ParallelRunner($this->loadedTest, $this->testMethods[$i], $this->socket);
					
					// need to add something to fauxThread so we can get the amount of waiting tasks.
					
					// placeholder code for testing
					$this->fauxPool->addTask($parallelRunner);
					$this->totalTests++;
					pcntl_signal_dispatch();
					$this->processSocket();
				}
			}
		}
		else
		{
			printf("Test %s was not run because it requires a newer version of Teency.\n", $class);
		}
	}
	
	private function runTest($methodName)
	{
		// need setup and tear down methods
		// need to add reasons why tests failed
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

				printf("%s...passed\n", $methodName);
				TestSuiteData::testPassed();
				$alreadyCounted = true;
			}
			else if($this->loadedTest->expectedException() === true && get_class($e) != $this->loadedTest->expectedExceptionName())
			{
				// need to rewrite the error so it sounds better
				printf("%s...failed: Test did not throw expected exception: %s %s\n", $methodName, $this->loadedTest->expectedExceptionName(), ErrorHandler::getErrors());
				TestSuiteData::testFailed();
				$alreadyCounted = true;
			}
			// might combine the else if and else statements
			else
			{
				printf("%s...failed: Test threw an exception and we weren't expecting one: %s.\nOther Errors: %s\n", $methodName, $e->getMessage(), ErrorHandler::getErrors());
				TestSuiteData::testFailed();
				$alreadyCounted = true;
			}
		}
		
		// if the test hasn't already been counted, so if there wasn't an exception
		if(!$alreadyCounted)
		{
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
		}
		
		$this->loadedTest->tearDownTest();
		$this->loadedTest->internalCleanupAfterEachTest();
		ErrorHandler::clearErrors();
	}
	
	private function processSocket()
	{
		$tmpStr = "";
		
		while(($raw = socket_read($this->socket[0], 1, PHP_BINARY_READ)) !== false)
		{
			
			if($raw === "\n")
			{
				$serialized = unserialize($tmpStr);
				
				if($serialized['pass'] === true)
				{
					printf("%s\n", $serialized['passOrFailStr']);
					TestSuiteData::testPassed();
				}
				else
				{
					printf("%s\n", $serialized['passOrFailStr']);
					TestSuiteData::testFailed();
				}
					$tmpStr = "";
			}
			else
			{
				$tmpStr .= $raw;
			}
		}
	}
	
	private function processFinishDrain()
	{
		while(pcntl_signal_dispatch() && $this->fauxPool->hasRunningTasks())
		{
			$this->processSocket();
			usleep(500);
		}
	}
	
	private function outputResults()
	{
	
		if($this->fauxPool !== null)
		{
			
			// waiting for processes to finish their work
			$this->processFinishDrain();
			
			// makes sure we get all of the data from the socket
			while($this->totalTests > TestSuiteData::totalTests())
			{
				$this->processSocket();
				usleep(500);
			}
		}
		
		
		printf("Total Tests: %d\nTests Passed: %d\nTests Failed: %d\nCompleted in %f\n", TestSuiteData::totalTests(), TestSuiteData::totalPassed(), TestSuiteData::totalFailed(), microtime(true) - $this->startTime);
	}
	
	public function __destruct()
	{
		// should short circuit if parallelTests is disabled it shouldn't check the fauxPool.
		if(!$this->parallelTests || $this->fauxPool->isParent())
		{
			$this->outputResults();
		}
	}
	
}
?>
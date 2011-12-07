<?php

class TestSuite
{
	private $loadedTest = null;
	private $testMethods = array();
	private $socket = null;
	private $fauxPool = null;
	private $totalTests = 0;
	
	
	public function load($class, $runInParallel = false, $numberInParallel = 2)
	{
		$this->testMethods = array();
		$this->loadedTest = new $class();
		$sockets = array();
		
		// building the threadPool and sockets
		if($runInParallel === true && $this->fauxPool === null)
		{
			// needs a try catch around this
			$this->fauxPool = new fauxThreadPool($numberInParallel);
			
			if(!socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $this->socket))
			{
				die(socket_strerror(socket_last_error()));
			}
			
			//socket_set_nonblock($this->socket[0]);
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
				if($runInParallel === false)
				{
					$this->runTest($this->testMethods[$i]);
				}
				else
				{
					$parallelRunner = new ParallelRunner($this->loadedTest, $this->testMethods[$i], $this->socket);
					
					// need to add something to fauxThread so we can get the amount of waiting tasks.
					
					// placeholder code for testing
					$this->fauxPool->addTask($parallelRunner);
					$this->totalTests++;
					pcntl_signal_dispatch();
					//$this->processSocket();
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
		// needs error checking
		//var_dump(debug_backtrace());
		
		$raw = socket_read($this->socket[0], 65536, PHP_NORMAL_READ);
		
		//var_dump($raw);
		//var_dump(socket_strerror(socket_last_error()));
		
		$serialized = unserialize($raw);
		//var_dump($serialized);
		
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
	}
	
	public function drainSocket()
	{
		while(pcntl_signal_dispatch() && $this->fauxPool->hasRunningTasks())
		{
			$this->processSocket();
			sleep(1);
		}
	}
	
	public function outputResults()
	{
	
		if($this->fauxPool !== null)
		{
			
			// just making sure there is no data left on the socket
			$this->drainSocket();
			//socket_close($this->socket);
			//socket_close($this->socketWrite);
			
			var_dump($this->totalTests);
			
			while($this->totalTests > TestSuiteData::totalTests())
			{
				$this->processSocket();
				//sleep(1);
			}
		}
		
		printf("Total Tests: %d\nTests Passed: %d\nTests Failed: %d\n", TestSuiteData::totalTests(), TestSuiteData::totalPassed(), TestSuiteData::totalFailed());
	}
	
	public function __destruct()
	{
		//var_dump(debug_backtrace());
		printf("destructor called\n");
		//printf(var_dump($this->socket));
	}
	
}
?>
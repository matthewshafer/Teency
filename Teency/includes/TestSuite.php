<?php

class TestSuite extends TestRunner
{
	private $loadedTest = null;
	private $testMethods = array();
	private $socket = null;
	private $fauxPool = null;
	private $totalTests = 0;
	private $parallelTests = false;
	private $numberOfParallelTests = 2;
	private $startTime = null;
	private $allowSkippedTests = true;
	
	
	public function runParallelTests($enable = true, $numberInParallel = 2)
	{
		$this->parallelTests = $enable;
		$this->numberOfParallelTests =  $numberInParallel;
	}

	public function allowSkippedTests($skip = true)
	{
		$this->allowSkippedTests = $skip;
	}
	
	public function load($class)
	{
		$this->testMethods = array();
		$this->loadedTest = new $class();
		$sockets = array();
		
		$this->startTime ?: $this->startTime = microtime(true);

		// private function call that sets up the thread pool if one does not exist yet
		$this->createThreadPool();
		
		// gets a list of all the class methods and counts how many of them there are
		$getMethods = get_class_methods($this->loadedTest);
		$ct = count($getMethods);
		$testMethodsCount = 0;
		
		for($i = 0; $i < $ct; $i++)
		{
			if(substr_compare($getMethods[$i], 'test', 0, 3) === 0)
			{
				$this->testMethods[] = $getMethods[$i];
				$testMethodsCount++;
			}
		}
		
		if($this->loadedTest->minTeencyVersion() > Teency::teencyVersion())
		{
			printf("Test %s was not run because it requires a newer version of Teency.\n", $class);
			return;
		}

		for($i = 0; $i < $testMethodsCount; $i++)
		{
			if(!$this->parallelTests)
			{
				$output = $this->runTest($this->loadedTest, $this->testMethods[$i], $this->allowSkippedTests);
				printf("%s\n", $output['passOrFailStr']);
				$this->processTestResult($output['result']);
				$this->totalTests++;
			}
			else
			{
				$parallelRunner = new ParallelRunner($this->loadedTest, $this->testMethods[$i], $this->socket, $this->allowSkippedTests);
				
				// need to add something to fauxThread so we can get the amount of waiting tasks.
				
				// placeholder code for testing
				$this->fauxPool->addTask($parallelRunner);
				$this->totalTests++;
				// ends up calling a callback in fauxthread which happens when a child finishes
				pcntl_signal_dispatch();
				$this->processSocket();
			}
		}
	}

	private function createThreadPool()
	{
		// building the threadPool and sockets
		if($this->parallelTests === true && $this->fauxPool === null)
		{
			// needs a try catch around this
			$this->fauxPool = new fauxThreadPool($this->numberOfParallelTests);
			
			if(!socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $this->socket))
			{
				// maybe we should have this revert to running the test normally
				die(socket_strerror(socket_last_error()));
			}
			
			// setting the reader to non-blocking so we don't stall out when reading data
			socket_set_nonblock($this->socket[0]);
		}
	}
	
	private function processSocket()
	{
		$tmpStr = "";
		
		while(($raw = socket_read($this->socket[0], 1, PHP_BINARY_READ)) !== false)
		{
			
			if($raw === "\n")
			{
				$serialized = unserialize($tmpStr);
				
				printf("%s\n", $serialized['passOrFailStr']);

				$this->processTestResult($serialized['result']);
				
				// setting the temp string to an empty one so we can continue processing	
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
	
		if($this->fauxPool)
		{
			
			// waiting for processes to finish their work
			$this->processFinishDrain();

			// doing 5 loops to check the socket for data, if there we don't get any and we have
			// less completed tests than we had sent to run we fail them
			$count = 5;

			for($i = 0; $i < $count; $i++)
			{
				if($this->totalTests === TestSuiteData::totalTests())
				{
					break;
				}

				$this->processSocket();
				usleep(500);
			}

			// checking to see if we have less reported tests than tests we sent off to run
			// if we do then we fail a bunch of tests and make the test suite fail
			if(($toFail = $this->totalTests - TestSuiteData::totalTests()) > 0)
			{
				for($i = 0; $i < $toFail; $i++)
				{
					TestSuiteData::testFailed();
				}
			}
		}
		
		
		printf("Total Tests: %d\nTests Passed: %d\nTests Skipped: %d\nTests Failed: %d\nCompleted in %f\n", 
			TestSuiteData::totalTests(), 
			TestSuiteData::totalPassed(), 
			TestSuiteData::totalSkipped(), 
			TestSuiteData::totalFailed(), 
			microtime(true) - $this->startTime
		);

		if(TestSuiteData::totalFailed() > 0)
		{
			exit(1);
		}
	}

	private function processTestResult($result)
	{
		switch($result)
		{
			case 'p':
				TestSuiteData::testPassed();
				break;
			case 'f':
				TestSuiteData::testFailed();
				break;
			case 's':
				TestSuiteData::testSkipped();
				break;
		}
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
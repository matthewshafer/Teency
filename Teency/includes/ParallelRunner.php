<?php
class ParallelRunner extends TestRunner implements fauxThreadRunner
{
	
	private $loadedTest;
	private $testMethod;
	private $socket;
	private $allowSkippedTests;
	
	public function __construct($loadedTest, $testMethod, $socketWrite, $allowSkippedTests)
	{
		$this->loadedTest = $loadedTest;
		$this->testMethod = $testMethod;
		$this->socket = $socketWrite;
		$this->allowSkippedTests = $allowSkippedTests;
	}
	
	public function run()
	{
		$socket = $this->socket;
		$method = $this->testMethod;

		$callback = function() use (&$socket, &$testMethod) { 
				$error = error_get_last();
				if($error['type'] === 1)
				{
					$arr = array();
					$arr['passOrFailStr'] = sprintf("%s...failed, fatal error", $testMethod);
					$arr['pass'] = false;
					$serial = serialize($arr) . "\n";
					socket_write($socket[1], $serial, strlen($serial));
				}
		};

		register_shutdown_function($callback);
		// runs the test and writes it to the socket
		$this->writeToSocket($this->runTest($this->loadedTest, $method, $this->allowSkippedTests));
		exit(0);
	}

	private function writeToSocket($data)
	{
		$serial = serialize($data) . "\n";
		socket_write($this->socket[1], $serial, strlen($serial));
	}
}

?>
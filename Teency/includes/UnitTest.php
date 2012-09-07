<?php

class UnitTest extends EqualityTests
{
	private $exceptionExpected = false;
	private $exceptionName = "";
	
	public function setUpTest()
	{
	
	}
	
	public function tearDownTest()
	{
	
	}
	
	public function expectedException()
	{
		return $this->exceptionExpected;
	}
	
	public function throwsException($exceptionName, $message)
	{
		$this->exceptionName = $exceptionName;
		$this->exceptionExpected = true;
	}

	public function errorCountShouldBe($count)
	{
		$errorCount = ErrorHandler::errorCount();
		if($errorCount !== $count)
		{
			throw new Exception("Expected error count $count but got an error count of $errorCount");
		}
	}

	public function clearErrors()
	{
		ErrorHandler::clearErrors();
	}
	
	public function expectedExceptionName()
	{
		return $this->exceptionName;
	}
	
	public function internalCleanupAfterEachTest()
	{
		$this->exceptionExpected = false;
		$this->exceptionName = "";
	}
	
	public function minTeencyVersion()
	{
		return null;
	}
}
?>
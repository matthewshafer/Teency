<?php

class UnitTest
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
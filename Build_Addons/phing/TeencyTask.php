<?php

require_once "phing/Task.php";

class TeencyTask extends Task
{
	
	private $teencyLocation = null;
	private $testSuite = null;
	
	public function setTeencyLocation($str)
	{
		$this->teencyLocation = $str;
	}
	
	public function setTestSuite($str)
	{
		$this->testSuite = $str;
	}
	
	public function init()
	{
		//
	}
	
	public function main()
	{
		require_once($this->teencyLocation . 'Teency.php');
		
		include($this->testSuite);
		
		if(TestSuiteData::totalFailed() > 0)
		{
			throw new BuildException("failed tests");
		}
	}
}
?>
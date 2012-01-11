<?php

class TestNotRunningConstructor
{
	private $test = 1;
	private $test2 = "test";

	public function __construct()
	{
		$this->test = 5;
		$this->test2 = "noTest";
	}
}
?>
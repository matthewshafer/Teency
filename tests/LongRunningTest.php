<?php

class LongRunningTest extends UnitTest
{

	public function runOnce()
	{
		
	}

	public function testLong1()
	{
		sleep(5);
		assert(1 === 1);
	}
	
	public function testLong2()
	{
		sleep(4);
		assert(false === false);
	}
	
	public function testLong3()
	{
		sleep(3);
		assert(1 === 1);
	}

}
?>
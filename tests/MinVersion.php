<?php

class MinVersion extends UnitTest
{
	public function minTeencyVersion()
	{
		return 999999999999;
	}

	public function testMinVersion()
	{
		throw new exception("should not have been called");
	}
}
?>
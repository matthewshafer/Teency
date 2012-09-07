<?php

class EqualityTests
{
	public function isEqual($first, $second)
	{
		if($first != $second)
		{
			ErrorHandler::equalityBacktrace("$first is not equal to $second", debug_backtrace());
		}
	}

	public function isExactlyEqual($first, $second)
	{
		if($first !== $second)
		{
			ErrorHandler::equalityBacktrace("$first is not exactly equal to $second", debug_backtrace());
		}
	}
}
?>
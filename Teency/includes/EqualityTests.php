<?php

class EqualityTests
{
	public function isEqual($first, $second)
	{
		if($first != $second)
		{
			if($this->areClosure($first, $second))
			{
				ErrorHandler::equalityBacktrace("Objects are not the same", debug_backtrace());
			}
			else
			{
				ErrorHandler::equalityBacktrace("$first is not equal to $second", debug_backtrace());
			}
		}
	}

	public function isExactlyEqual($first, $second)
	{
		if($first !== $second)
		{
			if($this->areClosure($first, $second))
			{
				ErrorHandler::equalityBacktrace("Objects are not the same", debug_backtrace());
			}
			else
			{
				ErrorHandler::equalityBacktrace("$first is not exactly equal to $second", debug_backtrace());
			}
		}
	}

	public function isNotEqual($first, $second)
	{
		if($first == $second)
		{
			if($this->areClosure($first, $second))
			{
				ErrorHandler::equalityBacktrace("Objects are the same", debug_backtrace());
			}
			else
			{
				ErrorHandler::equalityBacktrace("$first is equal to $second", debug_backtrace());
			}
		}
	}

	public function isNotExactlyEqual($first, $second)
	{
		if($first === $second)
		{

			if($this->areClosure($first, $second))
			{
				ErrorHandler::equalityBacktrace("Objects are the same", debug_backtrace());
			}
			else
			{
				ErrorHandler::equalityBacktrace("$first is exactly equal to $second", debug_backtrace());
			}
		}
	}

	private function areClosure($first, $second)
	{
		return $first instanceof closure || $second instanceof closure;
	}
}
?>
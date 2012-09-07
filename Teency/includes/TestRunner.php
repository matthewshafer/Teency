<?php

class TestRunner
{
	protected function runTest($loadedTest, $methodName, $allowSkippedTests)
	{
		$testResultArray = array();
		$alreadyCounted = false;
		
		try
		{
			$loadedTest->setUpTest();
			$loadedTest->$methodName();
		}
		catch(SkipTestException $e)
		{
			if($allowSkippedTests)
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...skipped: Not supported on this version of php", $methodName);
				$testResultArray['result'] = 's';
			}
			else
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...failed: Test did not throw expected exception: %s %s", $methodName, $loadedTest->expectedExceptionName(), ErrorHandler::getErrors());
				$testResultArray['result'] = 'f';
			}

			$alreadyCounted = true;
		}
		catch(Exception $e)
		{
			if($loadedTest->expectedException() === true && get_class($e) === $loadedTest->expectedExceptionName())
			{

				$testResultArray['passOrFailStr'] = sprintf("%s...passed", $methodName);
				$testResultArray['result'] = true; 
			}
			else if($loadedTest->expectedException() === true && get_class($e) != $loadedTest->expectedExceptionName())
			{
				// need to rewrite the error so it sounds better
				$testResultArray['passOrFailStr'] = sprintf("%s...failed: Test did not throw expected exception: %s %s", $methodName, $loadedTest->expectedExceptionName(), ErrorHandler::getErrors());
				$testResultArray['result'] = false;
			}
			// might combine the else if and else statements
			else
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...failed: Test threw an exception and we weren't expecting one: %s.  Other Errors: %s", $methodName, $e->getMessage(), ErrorHandler::getErrors());
				$testResultArray['result'] = false;
			}

			$alreadyCounted = true;
		}
		
		// if the test hasn't already been counted, so if there wasn't an exception
		if(!$alreadyCounted)
		{
			if($loadedTest->expectedException() === false && !ErrorHandler::haveErrors())
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...passed", $methodName);
				$testResultArray['result'] = true; 
			}
			else
			{
				$testResultArray['passOrFailStr'] = sprintf("%s...failed: %s", $methodName, ErrorHandler::getErrors());
				$testResultArray['result'] = false;
			}
		}
		
		$loadedTest->tearDownTest();
		$loadedTest->internalCleanupAfterEachTest();
		ErrorHandler::clearErrors();

		return $testResultArray;
	}
}
?>
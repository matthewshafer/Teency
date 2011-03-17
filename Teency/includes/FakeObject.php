<?php

class FakeObject
{
	private $fakeMethods = array();
	private $fakeVariables = array();
	private $fakeMethodArg = array();
	
	
	public function __construct()
	{
	
	}
	
	public function __call($name, $arguments)
	{
		$ct = count($this->fakeMethods);
		$returnValue = null;
		$found = false;
		$i = 0;
		
		while($i < $ct && !$found)
		{
			if($this->fakeMethods[$i]['name'] === $name)
			{
				$found = true;
				$returnValue = $this->fakeMethods[$i]['returnValue'];
				$this->fakeMethodArg[] = array('methodName' => $name, 'methodArguments' => $arguments);
			}
			
			++$i;
		}
		
		if(!$found)
		{
			trigger_error("Method does not exist in fake object", E_USER_ERROR);
		}
		
		return $returnValue;
	}
	
	// could possibly make it to addMethod allowed you to change the return value of the method if it was already created
	public function addMethod($name, $returnValue)
	{
		// doesn't check if the method already exists
		$this->fakeMethods[] = array('name' => $name, 'returnValue' => $returnValue);
	}
	
	public function getFakeMethodArgumentsArray()
	{
		return $this->fakeMethodArg;
	}
	
	public function __set($name, $val)
	{
		// the main reason for doing it this way is so we can see if a variable is set and then get it's return value.
		// if we were to use something like $this->fakeVariables[$name] = $val then if $val were null we would get a not-set error
		// this way we dont throw an error if we are returning a null value
		// it is different in recflectionHelper because those are actual objects we are referencing (just look at how i get the reference in reflection helper).
		
		$ct = count($this->fakeVariables);
		$i = 0;
		$found = false;
		
		// see if the value already exists and update it
		while($i < $ct && !$found)
		{
			if($this->fakeVariables[$i]['name'] === $name)
			{
				$found = true;
				$this->fakeVariables[$i]['returnValue'] = $val;
			}
			
			++$i;
		}
		
		// push the new value into the end of the array
		if(!$found)
		{
			$this->fakeVariables[] = array('name' => $name, 'returnValue' => $val);
		}
		
	}
	
	// just aliased to use __set
	public function addVariable($name, $val)
	{
		$this->__set($name, $val);
	}
	
	
	
	// needs to have a __get() and __set() so we can then add variables and whatnot
	
	public function __get($name)
	{
		$found = false;
		$i = 0;
		$ct = count($this->fakeVariables);
		$returnValue = null;
		
		while($i < $ct && !$found)
		{
			if($this->fakeVariables[$i]['name'] === $name)
			{
				$found = true;
				$returnValue = $this->fakeVariables[$i]['returnValue'];
			}
			
			++$i;
		}
		
		if(!$found)
		{
			trigger_error("Variable does not exist in fake object", E_USER_ERROR);
		}
		
		return $returnValue;
	}

}

?>
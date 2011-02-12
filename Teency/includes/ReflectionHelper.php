<?php

class ReflectionHelper
{
	
	private $reflectionClassContainer = array();
	
	public function __construct($refClass, $classObj, $methodsArray, $variablesArray)
	{
		$this->reflectionClassContainer['refClass'] = $refClass;
		$this->reflectionClassContainer['classObject'] = $classObj;
		$this->reflectionClassContainer['classMethods'] = $methodsArray;
		$this->reflectionClassContainer['classVariables'] = $variablesArray;
		
		//print_r($this->reflectionClassContainer['classMethods']);
		//print_r($this->reflectionClassContainer['classVariables']);
	}
	
	public function __call($method, $args)
	{
		$mRefProperty = &$this->reflectionClassContainer['classMethods'][$method];
		
		if(isset($mRefProperty))
		{
			$mRefProperty->invoke($this->reflectionClassContainer['classObject']);
		}
		else
		{
			trigger_error("Reflected Class Method \"$method\" Does Not Exist", E_USER_ERROR);
		}
	}
	
	public function __get($name)
	{
		$ret;
		
		$vRefProperty = &$this->reflectionClassContainer['classVariables'][$name];
		
		if(isset($vRefProperty))
		{
			$ret = $vRefProperty->getValue($this->reflectionClassContainer['classObject']);
		}
		else
		{
			trigger_error("Reflected Class Does Not Contain the Variable $name , Can Not Read", E_USER_ERROR);
		}
		
		return $ret;
	}
	
	public function __set($name, $value)
	{
		$vRefProperty = &$this->reflectionClassContainer['classVariables'][$name];
	
		if(isset($vRefProperty))
		{
			$vRefProperty->setValue($this->reflectionClassContainer['classObject'], $value);
		}
		else
		{
			trigger_error("Reflected Class Does Not Contain the Variable $name , Can Not Set");
		}
	}

}
?>
<?php

class FakeObject
{
	private $fakeMethods = array();
	
	
	public function __construct()
	{
	
	}
	
	public function __call($name, $arguments)
	{
		$ct = count($this->fakeMethods);
		$returnValue = null;
		$found = false;
		
		for($i = 0; $i < $ct; $i++)
		{
			if($this->fakeMethods[$i]['name'] === $name)
			{
				$found = true;
				$returnValue = $this->fakeMethods[$i]['returnValue'];
			}
		}
		
		if(!$found)
		{
			trigger_error("Method does not exist in fake object", E_USER_ERROR);
		}
		
		return $returnValue;
	}
	
	public function addMethod($name, $returnValue)
	{
		// doesn't check if the method already exists
		$this->fakeMethods[] = array('name' => $name, 'returnValue' => $returnValue);
	}

}

?>
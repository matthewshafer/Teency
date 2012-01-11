<?php

class ReflectClass
{
	private $className;
	private $classArgs;

	public function __construct($className, $classArgs)
	{
		$this->className = $className;
		$this->classArgs = $classArgs;
	}
	
	public function getReflection($runConstructor = true)
	{
		$varArr = array();
		$methodArr = array();
		$class;
		
		
		$ref = new ReflectionClass($this->className);
		$vars = $ref->getProperties();
		$methods = $ref->getMethods();
		
		//var_dump($ref->getConstructor());
		
		if($runConstructor === true)
		{
			// the only issue I can see from this is if you are trying to pass an array, in that case you can use an array with an array inside of it.
			if(is_array($this->classArgs))
			{
				$class = $ref->newInstanceArgs($this->classArgs);
			}
			else
			{
				$class = $ref->newInstance();
			}
		}
		else
		{
			$class = $ref->newInstanceWithoutConstructor();
		}
		
		foreach($vars as $var)
		{
			$var->setAccessible(true);
			$varArr[$var->getName()] = $var;
		}
		
		foreach($methods as $method)
		{
			$method->setAccessible(true);
			$methodArr[$method->getName()] = $method;
		}
		
		return new ReflectionHelper($ref, $class, $methodArr, $varArr);
	}

}
?>
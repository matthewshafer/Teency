<?php

class EqualityTest extends UnitTest
{
	public function testEqualBasic()
	{
		$this->isEqual(true, true);
	}

	public function testEqualAdvanced()
	{
		$fake = new FakeObject();
		$fake->addMethod('test', 1);

		$this->isEqual($fake->test(), 1);
	}

	public function testEqualDynamicTypeBasic()
	{
		$this->isEqual("0", false);
	}

	public function testEqualDynamicTypeAdvanced()
	{
		$fake = new FakeObject();
		$fake->addMethod('test', 1);

		$this->isEqual($fake->test(), true);
	}

	public function testEqualFailWhenNotEqual()
	{
		$this->isEqual(1, 0);
		$this->errorCountShouldBe(1);
		$this->clearErrors();
	}

	public function testExactlyEqualBasic()
	{
		$this->isExactlyEqual(true, true);
	}

	public function testExactlyEqualAdvanced()
	{
		$fake = new FakeObject();
		$fake->addMethod('test', 1);

		$this->isExactlyEqual($fake->test(), 1);
	}

	public function testDynamicTypeFail()
	{
		$this->isExactlyEqual("1", 1);
		$this->errorCountShouldBe(1);
		$this->clearErrors();
	}

	public function testExactlyEqualFailWhenNotEqual()
	{
		$this->isExactlyEqual(2, 1);
		$this->errorCountShouldBe(1);
		$this->clearErrors();
	}
}
?>
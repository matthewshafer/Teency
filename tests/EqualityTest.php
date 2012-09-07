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

		$object = Function() { return 1; };
		$object2 = Function() { return 2; };

		$this->isEqual($object, $object2);
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

		$object = Function() { return 1; };
		$object2 = Function() { return 2; };

		$this->isExactlyEqual($object, $object2);
		$this->errorCountShouldBe(1);
		$this->clearErrors();
	}

	public function testNotEqualSimple()
	{
		$this->isNotEqual(true, false);
	}

	public function testNotEqualAdvanced()
	{
		$this->isNotEqual("true", false);
	}

	public function testNotEqualFailWhenEqualSimple()
	{
		$this->isNotEqual(true, true);
		$this->errorCountShouldBe(1);
		$this->clearErrors();

		$this->isNotEqual("1", "1");
		$this->errorCountShouldBe(1);
		$this->clearErrors();
	}

	public function testNotEqualFailWhenEqualAdvanced()
	{
		$this->isNotEqual(1, true);
		$this->errorCountShouldBe(1);
		$this->clearErrors();

		$this->isNotEqual("10", 10);
		$this->errorCountShouldBe(1);
		$this->clearErrors();

		$object = Function() { return 1; };

		$this->isNotEqual($object, $object);
		$this->errorCountShouldBe(1);
		$this->clearErrors();
	}

	public function testNotExactlyEqualSimple()
	{
		$this->isNotExactlyEqual("1", "2");

		$this->isNotExactlyEqual(1, true);
	}

	public function testNotExactlyEqualAdvanced()
	{
		$this->isNotExactlyEqual(1, "2");

		$this->isNotExactlyEqual(1, "1");
	}

	public function testNotExactlyEqualFailWhenEqual()
	{
		$this->isNotExactlyEqual(1, 1);
		$this->errorCountShouldBe(1);
		$this->clearErrors();

		$this->isNotExactlyEqual("10", "10");
		$this->errorCountShouldBe(1);
		$this->clearErrors();

		$object = Function() { return 1; };

		$this->isNotExactlyEqual($object, $object);
		$this->errorCountShouldBe(1);
		$this->clearErrors();
	}
}
?>
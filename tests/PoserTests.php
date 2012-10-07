<?php

use Poser\Poser;

class PoserTests extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		Poser::delete(true, true);
		Poser::register();
	}

	public function testClassAlias()
	{
		Poser::alias(array(
			'AliasName' => 'Poser\\Poser',
		));

		$this->assertTrue(Poser::load('AliasName'));
		$this->assertFalse(Poser::load('OtherAliasName'));
	}

	public function testClosureAlias()
	{
		Poser::alias(array(
			'Get\*\This' => function($class)
			{
				return $class;
			}
		));

		$this->assertTrue(Poser::load('Get\\Poser\\Pose\\This'));
	}

	public function testAliassing()
	{
		Poser::alias(array(
			'MyTestAlias' => 'Poser\Pose',
		));

		$this->assertTrue(class_exists('MyTestAlias', true));
	}

	public function testPoseFactory()
	{
		$result = Poser::pose();

		$this->assertInstanceOf('Poser\\Pose', $result);
	}

	public function testPoseInstance()
	{
		$result = Poser::instance('testPoseInstance');

		$this->assertInstanceOf('Poser\\Pose', $result);
	}

	public function testPoserFactoryAutoRegister()
	{
		$poser = Poser::pose(true);

		$poser->alias(array(
			'PoserFactoryAutoRegister' => 'Poser\\Pose',
		));

		$this->assertTrue(class_exists('PoserFactoryAutoRegister', true));
	}

	public function testPoserInstanceAutoRegister()
	{
		$poser = Poser::pose(true);

		$poser->alias(array(
			'PoserInstanceAutoRegister' => 'Poser\\Pose',
		));

		$this->assertTrue(class_exists('PoserInstanceAutoRegister', true));
	}

	public function testPoserInstanceRetrieve()
	{
		$poser = Poser::instance('PoserInstanceRetrieve');
		$poser->alias(array(
			'PoserInstanceRetrieve' => 'Poser\Pose',
		));

		$other = Poser::instance('PoserInstanceRetrieve');

		$this->assertTrue($other->load('PoserInstanceRetrieve'));
	}
}
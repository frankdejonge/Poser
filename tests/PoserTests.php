<?php

use Poser\Poser;

class PoserTests extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		Poser::unregister();
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
		
		$this->assertTrue(Poser::load('Get\\Poser\Pose\\This'));
	}
	
	public function testAliassing()
	{
		Poser::alias(array(
			'MyTestAlias' => 'Poser\Pose',
		));
		
		$this->assertTrue(class_exists('MyTestAlias', true));
	}
}
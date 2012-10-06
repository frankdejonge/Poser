<?php

use Poser\Poser;

class PoserTests extends PHPUnit_Framework_TestCase
{
	public function testClassAlias()
	{	
		Poser::alias(array(
			'AliasName' => 'Poser\\Poser',
		));
		
		$this->assertTrue(Poser::load('AliasName'));
		$this->assertFalse(Poser::load('OtherAliasName'));
	}
}
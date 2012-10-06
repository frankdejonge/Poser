<?php

namespace Poser;

class Poser
{
	protected static $posers = array();

	public static function instance($name)
	{
		if ( ! isset(static::$posers[$name]))
		{
			static::$posers[$name] = new Pose();
		}
		
		return static::$posers[$name];
	}

	/**
	 * Static call forwarder.
	 *
	 * @param   string  $func  called function
	 * @param   array   $args  function arguments
	 * @return  mixed   function result
	 */
	public static function __callStatic($func, $args)
	{
		$instance = static::instance('__biggest_poser__');

		if ( ! method_exists($instance, $func))
		{
			throw new \BadMethodCallException('Call to undefined method '.$func);
		}

		return call_user_func_array(array($instance, $func), $args);
	}
}
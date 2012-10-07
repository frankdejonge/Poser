<?php

namespace Poser;

class Poser
{
	/**
	 * @var  array  $poses  Pose instances
	 */
	protected static $poses = array();

	/**
	 * Retrieve or create a Pose instance
	 *
	 * @param   string  $name      instance name
	 * @param   bool    $register  wether to register the autoload function
	 */
	public static function instance($name, $register = false)
	{
		if ( ! isset(static::$poses[$name]))
		{
			static::$poses[$name] = new Pose($register);
		}

		return static::$poses[$name];
	}

	/**
	 * Pose factory.
	 *
	 * @param   bool    $register  wether to register the autoload function
	 * @return  object  $this
	 */
	public static function pose($register = false)
	{
		return new Pose($register);
	}

	/**
	 * Delete a pose
	 *
	 * @param  string  $name        pose instance name
	 * @param  bool    $unregister  wether to unregister the autoload function before deleting
	 */
	public static function delete($pose, $unregister = true)
	{
		if ($pose === true)
		{
			$poses = static::$poses;
		}
		else
		{
			$poses = isset(static::$poses[$pose]) ? array($pose => static::$poses[$pose]) : array();
		}

		foreach ($poses as $pose => $instance)
		{
			if ($unregister)
			{
				$instance->unregister();
			}

			unset(static::$poses[$pose]);
		}
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
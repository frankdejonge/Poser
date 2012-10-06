<?php

namespace Poser;

class Poser
{
	/**
	 * @var  array  @map  alias mapping
	 */
	protected static $map = array();

	/**
	 * @var  string  $current  class currently autoloaded
	 */
	protected static $checkingClass;
	
	protected static $registered = false;

	/**
	 * Autoloader function. Checks for a matching alias,
	 * loads the destination class when found and creates
	 * the class alias.
	 *
	 * @param   string  $classname  the class to load
	 * @return  bool    wether the class was loaded
	 */
	public static function load($classname)
	{
		if($classname === static::$checkingClass)
		{
			return false;
		}

		foreach (static::$map as $pattern => $replacement)
		{
			$pattern = str_replace('\\*', '(.*)', preg_quote($pattern, '#'));

			if (preg_match('#^'.$pattern.'$#uD', $classname))
			{
				$replacement = str_replace('\\', '\\\\', $replacement);
				$class = preg_replace('#^'.$pattern.'$#uD', $replacement, $classname);

				if (class_exists($class, true))
				{
					class_alias($class, $classname);
					
					static::$checkingClass = null;

					return true;
				}
			}
		}

		static::$checkingClass = null;

		return false;
	}

	/**
	 * Adds one or more aliasses to the map.
	 *
	 * @param  array  $aliasses  aliasses to add
	 * @param  bool   @prepend   wether to prepend the aliasses to the map, false to append
	 */
	public static function alias(array $aliasses, $prepend = false)
	{
		static::$map = $prepend ? array_merge($aliasses, static::$map) : array_merge(static::$map, $aliasses);
	}

	/**
	 * Removes one or more aliasses from the map.
	 * This does not remove used aliasses.
	 *
	 * @param  mixed  $aliasses  aliasses to remove
	 */
	public static function unalias(array $aliasses)
	{
		foreach ($aliasses as $pattern => $replacement)
		{
			if (is_numeric($pattern) and isset(static::$map[$replacement]))
			{
				unset(static::$map[$replacement]);
			}
			elseif (isset(static::$map[$pattern]))
			{
				unset(static::$map[$pattern]);
			}
		}
	}

	/**
	 * Registers the autoloader function.
	 *
	 * @param  bool  $prepend  wether to prepend the loader to the autoloader stack.
	 */
	public static function register($prepend = true)
	{
		if ( ! static::$registered)
		{
			spl_autoload_register('Poser\Poser::load', true, $prepend);
			static::$registered = true;
		}
	}
}
<?php

namespace Poser;

class Pose
{
	/**
	 * @var  array  @map  alias mapping
	 */
	protected $map = array();

	/**
	 * @var  string  $current  class currently autoloaded
	 */
	protected $checkingClass;

	/**
	 * @var  bool  $registered  wether the autoloader function has been registered
	 */
	protected $registered = false;

	/**
	 * Autoloader function. Checks for a matching alias,
	 * loads the destination class when found and creates
	 * the class alias.
	 *
	 * @param   string  $classname  the class to load
	 * @return  bool    wether the class was loaded
	 */
	public function load($classname)
	{
		if($classname === $this->checkingClass)
		{
			return false;
		}

		foreach ($this->map as $pattern => $replacement)
		{
			$pattern = str_replace('\\*', '(.*)', preg_quote($pattern, '#'));

			if (preg_match('#^'.$pattern.'$#uD', $classname, $matches))
			{
				if ($replacement instanceof \Closure)
				{
					array_shift($matches);
					$class = call_user_func_array($replacement, $matches);
				}
				else
				{
					$replacement = str_replace('\\', '\\\\', $replacement);
					$class = preg_replace('#^'.$pattern.'$#uD', $replacement, $classname);
				}

				if (class_exists($class, true))
				{
					class_alias($class, $classname);
					$this->checkingClass = null;

					return true;
				}
			}
		}

		$this->checkingClass = null;

		return false;
	}

	/**
	 * Adds one or more aliasses to the map.
	 *
	 * @param   array   $aliasses  aliasses to add
	 * @param   bool    $prepend   wether to prepend the aliasses to the map, false to append
	 * @return  object  $this
	 */
	public function alias(array $aliasses, $prepend = false)
	{
		$this->map = $prepend ? array_merge($aliasses, $this->map) : array_merge($this->map, $aliasses);
	
		return $this;
	}

	/**
	 * Removes one or more aliasses from the map.
	 * This does not remove used aliasses.
	 *
	 * @param   mixed   $aliasses  aliasses to remove
	 * @return  object  $this
	 */
	public function unalias(array $aliasses)
	{
		foreach ($aliasses as $pattern => $replacement)
		{
			if (is_numeric($pattern) and isset($this->map[$replacement]))
			{
				unset($this->map[$replacement]);
			}
			elseif (isset($this->map[$pattern]))
			{
				unset($this->map[$pattern]);
			}
		}
		
		return $this;
	}

	/**
	 * Registers the autoloader function.
	 *
	 * @param   bool    $prepend  wether to prepend the loader to the autoloader stack.
	 * @return  object  $this
	 */
	public function register($prepend = true)
	{
		if ( ! $this->registered)
		{
			spl_autoload_register(array($this, 'load'), true, $prepend);
			$this->registered = true;
		}
		
		return $this;
	}

	/**
	 * Unregisters the autoloader function.
	 *
	 * @return  object  $this
	 */
	public function unregister()
	{
		if ($this->registered)
		{
			spl_autoload_unregister(array($this, 'load'));
			$this->registered = false;
		}
		
		return $this;
	}
}
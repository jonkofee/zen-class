<?php

namespace Core;

trait Singleton
{

	/**
	 * @var Object
	 */
	protected static $instance;

	final public static function getInstance(): self
	{
		return isset(static::$instance)
			? static::$instance
			: static::$instance = new static;
	}

}

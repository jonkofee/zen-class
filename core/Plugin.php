<?php

namespace Core;

abstract class Plugin
{

	/**
	 * @var Dispatcher
	 */
	protected $dispatcher;

	public function __construct(Dispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

}

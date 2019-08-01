<?php

namespace Core\Exception;

use Core\Exception;

class ServerError extends Exception
{

	public function __construct(string $message = 'Server error', int $code = 500)
	{
		parent::__construct($message, $code, null);
	}

}

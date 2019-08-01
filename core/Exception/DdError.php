<?php

namespace Core\Exception;

use Core\Exception;

class DdError extends Exception
{

	public function __construct(string $message = 'Database error', int $code = 500)
	{
		parent::__construct($message, $code, null);
	}

}

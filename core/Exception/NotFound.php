<?php

namespace Core\Exception;

use Core\UserException;

class NotFound extends UserException
{

	public function __construct(string $message = 'Not found', int $code = 404)
	{
		parent::__construct($message, $code, null);
	}

}

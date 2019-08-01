<?php

namespace Core\Exception;

use Core\UserException;

class Unauthorized extends UserException
{

	public function __construct(string $message = 'Unauthorized', int $code = 401)
	{
		parent::__construct($message, $code, null);
	}

}

<?php

namespace Core\Exception;

use Core\UserException;

class BadRequest extends UserException
{

	public function __construct(string $message = 'Bad Request', int $code = 400)
	{
		parent::__construct($message, $code, null);
	}

}

<?php

namespace Core;

class Email
{

	use Singleton;

	/**
	 * @var string
	 */
	private $from;

	public function __construct(string $from)
	{
		$this->from = $from;

		self::$instance = $this;
	}

	public function send(string $to, string $subject, string $body): bool
	{
		return mail($to, $subject, $body, [ 'From' => $this->from ]);
	}

}

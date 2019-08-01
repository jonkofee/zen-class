<?php

namespace Model;

use Core\Model;

class Participant extends Model
{

	/**
	 * @var string
	 */
	public static $tableName = 'Participant';

	/**
	 * @ColumnName ID
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * @ColumnName Name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @ColumnName Email
	 *
	 * @var string
	 */
	protected $email;

	/**
	 * @return string[]
	 */
	public function jsonSerialize(): array
	{
		return [
			'ID' => $this->id,
			'Email' => $this->email,
			'Name' => $this->name,
		];
	}

}

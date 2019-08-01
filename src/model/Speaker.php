<?php

namespace Model;

use Core\Model;

class Speaker extends Model
{

	/**
	 * @var string
	 */
	public static $tableName = 'Speaker';

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
	 * @return string[]
	 */
	public function jsonSerialize(): array
	{
		return [
			'ID' => $this->id,
			'Name' => $this->name,
		];
	}

}

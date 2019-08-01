<?php

namespace Model;

use Core\Model;

/**
 * @method static self findByName($tableName)
 */
class Table extends Model
{

	/**
	 * @var string
	 */
	protected static $tableName = 'Tables';

	/**
	 * @ColumnName Name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @ColumnName Hide
	 *
	 * @var bool
	 */
	protected $hide;

	public function isHide(): bool
	{
		return $this->hide;
	}

}

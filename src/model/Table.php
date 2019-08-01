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
	 * @ColumnName name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @ColumnName hide
	 *
	 * @var bool
	 */
	protected $hide;

	public function isHide(): bool
	{
		return $this->hide;
	}

}

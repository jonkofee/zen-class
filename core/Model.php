<?php

namespace Core;

use Core\Exception\ServerError;
use DateTime;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;

/**
 * @method static self find(int $id)
 * @property string tableName
 */
abstract class Model implements JsonSerializable
{

	/**
	 * @var string
	 */
	protected static $tableName;

	/**
	 * @ColumnName id
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * @var DB
	 */
	protected $db;

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data = [])
	{
		$this->db = DB::getInstance();

		$this->setupData($data);
	}

	/**
	 * @param string[] $arguments
	 */
	public static function __callStatic(string $name, array $arguments)
	{
		switch (true) {
			case preg_match('$^find$', $name):
				$find = str_replace('find', '', $name);
				if (!empty($find)) {
					if (!preg_match('$^By.+$', $find)) {
						break;
					}

					$find = str_replace('By', '', $find);
				}

				return self::finder($find, $arguments[0]);
		}

		trigger_error('Call to undefined method ' . get_called_class() . '::' . $name . '()', E_USER_ERROR);
	}

	public static function all() {
		$className = get_called_class();

		$result = DB::getInstance()->query("
			SELECT *
			FROM `{$className::$tableName}`
		");

		$data = [];
		foreach ($result as $item) {
			$data[] = new $className($item);
		}

		return $data;
	}

	public function save(): void
	{
		$table = get_called_class()::$tableName;
		$id = $this->id;

		$sqlDataString = $this->buildSqlDataString();

		if ($id) {
			//Update
			$this->db->query("
				UPDATE `$table`
				SET $sqlDataString
				WHERE `id` = $id
			");
		} else {
			//Create
			$this->db->query("
				INSERT INTO `$table`
				SET $sqlDataString
			");

			$this->id = (int) $this->db->getConnection()->lastInsertId();
		}
	}

	public function delete(): void
	{
		$table = get_called_class()::$tableName;
		$id = $this->id;

		if (empty($id)) {
			return;
		}

		$this->db->query("
			DELETE
			FROM `$table`
			WHERE id = $id
		");
	}

	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param mixed $value
	 * @throws Exception
	 *
	 * @return self[] | self | null
	 */
	private static function finder(string $name, $value)
	{
		$name = lcfirst($name);

		if (empty($name)) {
			$name = 'id';
		}

		$className = get_called_class();

		$reflector = new ReflectionClass($className);

		if (!$reflector->hasProperty($name)) {
			throw new Exception("Поля '$name' не существует в моделе '$className'"); //@todo изменить тип исключения, чтобы пользователь не видел этой ошибки
		}

		$docBlock = $reflector->getProperty($name)->getDocComment();

		if (!$docBlock) {
			throw new Exception("У поля '$name' не существует док блока"); //@todo изменить тип исключения, чтобы пользователь не видел этой ошибки
		}

		if (!preg_match('/@ColumnName (?<name>\w+)/', $docBlock, $match)) {
			throw new Exception("У поля '$name' не существует док блока @ColumnName"); //@todo изменить тип исключения, чтобы пользователь не видел этой ошибки
		}

		$field = $match['name'];

		$isStringColumn = !preg_match('/@var (int|integer|float)/', $docBlock);
		if ($isStringColumn) {
			$value = "'$value'";
		}

		$result = DB::getInstance()->query("
			SELECT *
			FROM `{$className::$tableName}`
			WHERE $field = $value
		");

		if (empty($result)) {
			return [];
		}

		$data = [];
		foreach ($result as $item) {
			$data[] = new $className($item);
		}

		return $data;
	}

	/**
	 * @param mixed[] $data
	 * @throws Exception
	 *
	 * @return self
	 */
	private function setupData(array $data): void
	{
		$fields = [];
		$reflector = new ReflectionClass(get_called_class());

		foreach ($reflector->getProperties() as $property) {
			$docBlock = $property->getDocComment();


			if (!$docBlock || !preg_match('/@ColumnName (?<name>\w+)/', $docBlock, $match)) {
				continue;
			}

			$field = $match['name'];

			$fields[$property->name] = $field;

			if (!array_key_exists($field, $data)) {
				$data[$field] = null;
				continue;
			}
			if (preg_match('/@var (?<type>\w+)/', $docBlock, $match)) {
				switch ($match['type']) {
					case 'int':
					case 'integer':
						$data[$field] = (int) $data[$field];
						break;
					case 'float':
						$data[$field] = (float) $data[$field];
						break;
					case 'DateTime':
						$data[$field] = new DateTime($data[$field]);
						break;
					case 'bool':
					case 'boolean':
						$data[$field] = (bool) $data[$field];
						break;
					case 'array':
						if (empty($data[$field])) {
							$data[$field] = [];
						} elseif (!is_array($data[$field])) {
							$data[$field] = json_decode($data[$field]);
						}
						break;
				}
			}
		}

		foreach ($fields as $field => $columnName) {
			$this->$field = $data[$columnName];
		}
	}

	/**
	 * @throws ReflectionException
	 *
	 * @return string[]
	 */
	private function prepareData(): array
	{
		$data = [];
		$reflector = new ReflectionClass(get_called_class());

		foreach ($reflector->getProperties() as $property) {
			$docBlock = $property->getDocComment();

			if (!$docBlock || !preg_match('/@ColumnName (?<name>\w+)/', $docBlock, $match)) {
				continue;
			}

			$field = $match['name'];

			$propertyName = $property->getName();
			$propertyValue = $this->$propertyName;
			if (empty($propertyValue)) {
				continue;
			}

			$data[$field] = $this->$propertyName;
		}

		return $data;
	}

	/**
	 * @throws ReflectionException
	 * @throws ServerError
	 */
	private function buildSqlDataString(): string
	{
		$data = $this->prepareData();
		$arr = [];

		foreach ($data as $column => $value) {
			if ($value instanceof DateTime) {
				$value = $value->format('Y-m-d H:i:s');
			}
			if (is_array($value)) {
				$value = !empty($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null;
			}

			switch (true) {
				case preg_match('@^\d+$@', $value):
					$sqlValue = $value;
					break;
				case is_string($value):
					$sqlValue = "'$value'";
					break;
				case is_bool($value):
					$sqlValue = $value ? 'true' : 'false';
					break;
				case is_null($value):
					$sqlValue = 'null';
					break;
				default:
					throw new ServerError();
			}

			$arr[] = "`$column` = " . $sqlValue;
		}

		return implode(', ', $arr);
	}

	/**
	 * @return string[]
	 */
	public function jsonSerialize(): array
	{
		return [];
	}

}

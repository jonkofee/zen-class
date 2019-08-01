<?php

namespace Core;

use Core\Exception\DdError;
use PDO;

class DB
{

	use Singleton;

	/**
	 * @var PDO
	 */
	private $connection;

	/**
	 * @param string[] $options
	 */
	public function __construct(array $options)
	{
		$this->connection = new PDO(
			"mysql:host={$options['host']};dbname={$options['name']};charset=utf8;",
			$options['username'],
			$options['password']
		);

		self::$instance = $this;
	}

	public function getConnection(): PDO
	{
		return $this->connection;
	}

	/**
	 * @param string $sql
	 * @throws DdError
	 *
	 * @return mixed[]
	 */
	public function query(string $sql): array
	{
		$sql = preg_replace('~\s+~', ' ', $sql);
		$sql = trim($sql);

		$statement = $this->connection->prepare($sql);

		if ($statement->execute() === false) {
			throw new DdError($statement->errorInfo()[2]);
		}

		return $statement->fetchAll();
	}

}

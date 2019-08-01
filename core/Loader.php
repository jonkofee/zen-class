<?php

namespace Core;

class Loader
{

	/**
	 * @var string[]
	 */
	private static $dirs;

	/**
	 * @param string[] $dirs
	 */
	public function __construct(array $dirs = [])
	{
		self::$dirs = $dirs;
		spl_autoload_register('self::autoLoad');
	}

	/**
	 * @throws Exception
	 */
	public static function autoLoad(string $class): bool
	{
		$dirs = self::$dirs;
		$dirs[] = __DIR__ . '/..';
		$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
		foreach ($dirs as $start) {
			$file = $start . DIRECTORY_SEPARATOR . $fileName;
			if (self::loadFile($file)) {
				return true;
			}
		}
		throw new Exception("Не получается загрузить класс $class");
	}

	private static function loadFile(string $file): bool
	{
		if (file_exists($file)) {
			require_once $file;

			return true;
		}
		return false;
	}

}
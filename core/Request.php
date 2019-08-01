<?php
namespace Core;

class Request
{

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var string[]
	 */
	private $headers = [];

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string[]
	 */
	private $data = [];

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->headers = getallheaders();
		$this->path = trim($_SERVER['REQUEST_URI'], '/');
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getHeader(string $name): ?string
	{
		return $this->headers[$name] ?? null;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @return string[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->data[$name] ?? null;
	}

	public function buildData(): void
	{
		$data = $this->getInput();
		foreach ($data as &$value) {
			if (is_string($value)) {
				$value = trim($value);
			}

			if (preg_match('/^(\d+)$/', $value)) {
				$value = (int) $value;
			}
		}

		$this->data = $data;
	}

	/**
	 * @return string[]
	 */
	private function getInput(): array
	{
		$requestArr = $_REQUEST;
		$contentType = explode(';', $_SERVER['CONTENT_TYPE'])[0];
		$input = file_get_contents('php://input');
		$tmp = [];

		switch ($contentType) {
			case 'application/x-www-form-urlencoded':
				parse_str($input, $tmp);
				break;
			case 'application/json':
				$tmp = json_decode($input, true) ?? [];
				break;
		}

		return array_merge($requestArr, $tmp);
	}

}

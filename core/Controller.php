<?php
namespace Core;

abstract class Controller
{

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * @var string[]
	 */
	protected $validateRules = [];

	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;

		$this->response = $response;
	}

	/**
	 * @param string $name
	 * @param string[] $arguments
	 * @throws Exception
	 *
	 * @return Response
	 */
	public function __call(string $name, array $arguments): Response
	{
		$actionName = $name . 'Action';

		if (!method_exists($this, $actionName)) {
			throw new Exception("Метод '$name' не существует в контроллере '" . self::class . "'", 500);
		}

		$result = call_user_func_array([$this, $actionName], $arguments);

		$this->response->setBody($result);

		return $this->response;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->request->$name;
	}

	/**
	 * @param int $code
	 *
	 * @return Controller
	 */
	protected function setCode(int $code): self
	{
		$this->response->setCode($code);

		return $this;
	}

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @return Controller
	 */
	protected function addHeader(string $key, string $value): self
	{
		$this->response->addHeader($key, $value);

		return $this;
	}

	protected function setCookie(string $name, string $value, bool $httpOnly = false): self
	{
		setcookie($name, $value, time() + 86400, '/', $_ENV['DOMAIN'], false, $httpOnly);

		return $this;
	}

	protected function removeCookie(string $name): self
	{
		setcookie($name, null, -1, '/');

		return $this;
	}

	/**
	 * @return string[] | null
	 */
	protected function getCurrentUser(): ?array
	{
		return Dispatcher::$currentUser;
	}

	/**
	 * @param string[] $fields
	 * @throws Exception
	 */
	public function validate(array $fields): void
	{
		$errors = [];

		foreach ($fields as $field) {
			if (!isset($this->validateRules[$field])) {
				throw new Exception("Не установлено правило валидации для поля $field", 500);
			}

			$validateRule = $this->validateRules[$field];

			if (isset($validateRule['regexp'])) {
				if (!preg_match($validateRule['regexp'], $this->{$field})) {
					$errors[] = $validateRule['message'];
				}
				continue;
			}

			if (isset($validateRule['filter'])) {
				if (!filter_var($this->{$field}, $validateRule['filter'])) {
					$errors[] = $validateRule['message'];
				}
			}
		}

		if ($errors) {
			throw new Exception(implode(', ', $errors), 400);
		}
	}

	public function setMessage(string $message): self
	{
		$this->response->setMessage($message);

		return $this;
	}

}

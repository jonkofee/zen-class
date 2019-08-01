<?php

namespace Core;

use Core\Exception\NotFound;
use ReflectionClass;
use ReflectionException;

class Router
{

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var ConfigIni
	 */
	private $routes;

	/**
	 * @var string
	 */
	private $controller;

	/**
	 * @var string
	 */
	private $action;

	/**
	 * @var string
	 */
	private $regExp;

	/**
	 * @param Request $request
	 * @throws Exception
	 */
	public function __construct(Request $request)
	{
		$config = new ConfigIni('src/config/router.ini');

		$this->request = $request;
		$this->routes = $config;
	}

	/**
	 * @throws Exception
	 */
	public function handle(): self
	{
		$route = $this->findRoute();

		$this->controller = $route['controller'];
		$this->action = $route['action'];

		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function getController(): string
	{
		if (!$this->controller) {
			$message = "Не установлен контроллер для маршрута '{$this->request->getMethod()} {$this->request->getPath()}'";
			throw new Exception($message, 500);
		}

		return $this->controller;
	}

	/**
	 * @throws Exception
	 */
	public function getAction(): string
	{
		if (!$this->action) {
			$message = "Не установлен метод-обработчик для маршрута '{$this->request->getMethod()} {$this->request->getPath()}'";
			throw new Exception($message, 500);
		}

		return $this->action;
	}

	/**
	 * @throws NotFound
	 *
	 * @return string[]
	 */
	private function findRoute(): array
	{
		$uri = $this->request->getPath();
		$method = $this->request->getMethod();
		foreach ($this->routes as $route) {
			if ($route['method'] !== $method) {
				continue;
			}

			$regExp = "@^{$route['path']}$@";

			if (preg_match($regExp, $uri)) {
				$this->regExp = $regExp;

				return [
					'controller' => $route['controller'],
					'action' => $route['action'],
				];
			}
		}

		$default = $this->getDefaultAction($uri, $method);

		if (!empty($default)) {
			return $default;
		}

		throw new NotFound('Такой маршрут отсутствует', 404);
	}

	/**
	 * @return string[]
	 */
	private function getDefaultAction(string $uri, string $method): array
	{
		$result = [];
		$arrUri = explode('/', $uri);

		$controller = $arrUri[0] ? $arrUri[0] : 'index';
		$action = $arrUri[1] ?? 'index';

		$methodName = $action . $method . 'Action';

		try {
			$reflector = new ReflectionClass('Controller\\' . $controller);

			if ($reflector->hasMethod($methodName)) {
				$result = [
					'controller' => $controller,
					'action' => $action . $method,
				];
			}
		} catch (Exception | ReflectionException $e) {
		}

		return $result;
	}

	public function getRegExp(): ?string
	{
		return $this->regExp;
	}

}

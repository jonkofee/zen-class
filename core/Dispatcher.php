<?php
namespace Core;

use Core\Interfaces\Executable;

class Dispatcher
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
	 * @var Router
	 */
	public $router;

	/**
	 * @var mixed[] | null
	 */
	public static $currentUser;

	/**
	 * @var Executable[]
	 */
	private $beforeHandlers = [];

	/**
	 * @var Executable[]
	 */
	private $beforeRouterHandlers = [];

	/**
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->request = new Request();
		$this->router = new Router($this->request);
		$this->response = new Response();
	}

	/**
	 * @throws Exception
	 *
	 * @return Response
	 */
	public function handle(): Response
	{
		$this->beforeRouterHandle();

		$this->request->buildData();

		$this->router->handle();

		$this->beforeHandle();

		$controller = $this->getController();
		$actionName = $this->getAction();

		return call_user_func_array([$controller, $actionName], $this->getActionArguments());
	}

	public function addBeforeHandler(string $className): self
	{
		$this->beforeHandlers[] = new $className($this);

		return $this;
	}

	public function addBeforeRouterHandler(string $className): self
	{
		$this->beforeRouterHandlers[] = new $className($this);

		return $this;
	}

	/**
	 * @throws Exception
	 *
	 * @return Controller
	 */
	private function getController(): Controller
	{
		$controllerClassName = 'Controller\\' . $this->router->getController();

		return new $controllerClassName($this->request, $this->response);
	}

	/**
	 * @throws Exception
	 */
	public function getAction(): string
	{
		return $this->router->getAction();
	}

	public function getRequest(): Request
	{
		return $this->request;
	}

	public function getResponse(): Response
	{
		return $this->response;
	}

	private function beforeHandle(): void
	{
		foreach ($this->beforeHandlers as $beforeHandle) {
			$beforeHandle->exec();
		}
	}

	private function beforeRouterHandle(): void
	{
		foreach ($this->beforeRouterHandlers as $beforeRouterHandler) {
			$beforeRouterHandler->exec();
		}
	}

	/**
	 * @return string[]
	 */
	private function getActionArguments(): array
	{
		$regExp = $this->router->getRegExp();

		if (!$regExp) {
			return [];
		}

		preg_match($regExp, $this->request->getPath(), $match, 0);

		unset($match[0]);

		return $match;
	}

}

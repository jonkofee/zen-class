<?php

namespace Core\Plugin;

use Core\Dispatcher;
use Core\Interfaces\Executable;
use Core\Plugin;
use Core\Request;
use Core\Response;

class CORS extends Plugin implements Executable
{

	/**
	 * @var Request
	 */
	private $request;
	/**
	 * @var Response
	 */
	private $response;

	public function __construct(Dispatcher $dispatcher)
	{
		parent::__construct($dispatcher);

		$this->request = $dispatcher->getRequest();
		$this->response = $dispatcher->getResponse();
	}

	public function exec(): void
	{
		$this->response->addHeader('Access-Control-Allow-Origin', $this->request->getHeader('Origin'));
		$this->response->addHeader('Access-Control-Allow-Credentials', 'true');

		if ($this->isPreflightRequest()) {
			if (!empty($this->request->getHeader('Access-Control-Request-Method'))) {
				$this->response->addHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE');
			}

			$requestHeaders = $this->request->getHeader('Access-Control-Request-Headers');
			if (!empty($requestHeaders)) {
				$this->response->addHeader('Access-Control-Allow-Headers', $requestHeaders);
			}

			echo $this->response->setCode(200);
			exit;
		}
	}

	private function isPreflightRequest(): bool
	{
		return $this->request->getMethod() === 'OPTIONS';
	}

}

<?php
namespace Core;

class Response
{

	/**
	 * @var string[]
	 */
	private $headers = [];

	/**
	 * @var int
	 */
	private $code;

	/**
	 * @var string[]
	 */
	private $body = [];

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @param string | string[] $body
	 * @param int $code
	 */
	public function __construct($body = null, int $code = 200)
	{
		$this->headers['Content-type'] = 'application/json';
		$this->code = $code;
		$this->body = $body;
	}

	public function setCode(int $code): self
	{
		$this->code = $code;

		return $this;
	}

	public function setMessage(string $message): self
	{
		$this->message = $message;

		return $this;
	}

	public function addHeader(string $key, ?string $value): self
	{
		$this->headers[$key] = $value;

		return $this;
	}

	/**
	 * @param string | string[] $body
	 */
	public function setBody($body): self
	{
//		if (!is_array($body)) {
//			$body = [$body];
//		}

		$this->body = $body;

		return $this;
	}

	public function __toString(): string
	{
		foreach ($this->headers as $key => $value) {
			header("$key: $value");
		}

		http_response_code($this->code);

		$isSuccess = $this->code >= 200 && $this->code < 300;

		$result = [
			'status' => $isSuccess ? 'ok' : 'error',
		];

		if (!is_null($this->message)) {
			$result['message'] = (string) $this->message;
		}

		if (!is_null($this->body)) {
			$result['playload'] = $this->body ? $this->body : (object) [];
		}

		return json_encode($result);
	}

}

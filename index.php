<?php
require 'core/Loader.php';

use Core\DB;
use Core\Dispatcher;
use Core\Loader;
use Core\UserException;

new Loader(['src' ]);

new DB([
	'host' => $_ENV['DATABASE_HOST'],
	'name' => $_ENV['DATABASE_NAME'],
	'username' => $_ENV['DATABASE_USERNAME'],
	'password' => $_ENV['DATABASE_PASSWORD'],
]);

try {
	$dispatcher = new Dispatcher();

	echo $dispatcher->handle();
} catch (UserException $e) {
	$response = $dispatcher->getResponse();

	$response
		->setCode($e->getCode())
		->setMessage($e->getMessage())
	;

	echo $response;
}
//catch (Throwable $e) {
//	$response = $dispatcher->getResponse();
//
//	$response
//		->setCode(500)
//		->setMessage('Server error')
//	;
//
//	echo $response;
//}

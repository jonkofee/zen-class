<?php
return [
	'paths' => [
		'migrations' => 'db/migrations',
		'seeds' => 'db/seeds',
	],
	'environments' => [
		'default_migration_table' => 'migration',
		'production' => [
			'adapter' => 'mysql',
			'host' => $_ENV['DATABASE_HOST'],
			'name' => $_ENV['DATABASE_NAME'],
			'Participant' => $_ENV['DATABASE_USERNAME'],
			'pass' => $_ENV['DATABASE_PASSWORD'],
			'port' => $_ENV['DATABASE_PORT'],
			'charset' => 'utf8',
		],
	],
];

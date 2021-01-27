<?php
return [
	'default' => [
		'db_host'	  => env('DB_HOST'),
		'db_port'	  => env('DB_PORT'),
		'db_database' => env('DB_DATABASE'),
		'db_username' => env('DB_USERNAME'),
		'db_password' => env('DB_PASSWORD'),
		'db_charset'  => env('DB_CHARSET'),
	],
	'static' => [
		'db_host'	  => env('STATIC_DB_HOST'),
		'db_port'	  => env('STATIC_DB_PORT'),
		'db_database' => env('STATIC_DB_DATABASE'),
		'db_username' => env('STATIC_DB_USERNAME'),
		'db_password' => env('STATIC_DB_PASSWORD'),
		'db_charset'  => env('STATIC_DB_CHARSET'),
	],
];
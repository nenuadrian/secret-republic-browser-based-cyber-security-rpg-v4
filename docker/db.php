<?php
/**
 * Database configuration for Docker deployments.
 *
 * The container entrypoint copies this file to fuel/app/config/db.php when
 * DB_HOST is set, so the connection is driven entirely by environment
 * variables (see compose.yaml). For non-Docker installs the web setup wizard
 * at /setup writes a static db.php instead.
 */
$env = static fn (string $key, string $default = ''): string => (string) (getenv($key) !== false ? getenv($key) : $default);

return array(
	'default' => array(
		'type' => 'pdo',
		'connection' => array(
			'dsn'      => sprintf(
				'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
				$env('DB_HOST', 'db'),
				$env('DB_PORT', '3306'),
				$env('DB_NAME', 'secretrepublic')
			),
			'username' => $env('DB_USER', 'secretrepublic'),
			'password' => $env('DB_PASSWORD'),
		),
		'charset' => 'utf8mb4',
		// The game was written against MySQL's permissive SQL mode.
		'command' => "SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'",
	),
);

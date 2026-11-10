<?php
/**
 * PHPUnit bootstrap: loads Composer dependencies and the app's models without
 * booting the full FuelPHP request cycle (no database needed).
 */
require dirname(__DIR__) . '/fuel/vendor/autoload.php';

defined('APPPATH') or define('APPPATH', dirname(__DIR__) . '/fuel/app/');

// Minimal stand-in for Fuel's base model class.
if (!class_exists('Model', false)) {
	class Model {}
}

// Model\Foo => fuel/app/classes/model/foo.php
spl_autoload_register(static function (string $class): void {
	if (strncmp($class, 'Model\\', 6) !== 0) {
		return;
	}
	$file = APPPATH . 'classes/model/' . strtolower(str_replace('\\', '/', substr($class, 6))) . '.php';
	if (is_file($file)) {
		require $file;
	}
});

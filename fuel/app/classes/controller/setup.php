<?php

use Model\Setup;

/**
 * First-run setup wizard (/setup).
 *
 * - No fuel/app/config/db.php: asks for database credentials and the admin
 *   account, verifies the connection, imports fuel/app/db.sql, writes db.php
 *   and creates the admin.
 * - db.php already present (e.g. generated from environment variables in
 *   Docker) but the database is empty: only asks for the admin account.
 * - Already installed: redirects to the home page.
 */
class Controller_Setup extends Controller
{
	/** Group id that grants access to the Cardinal (admin) dashboard. */
	const ADMIN_GROUP = 3;

	public function action_index()
	{
		$configured = Setup::has_db_config();
		$pdo = null;

		if ($configured) {
			try {
				$pdo = Setup::connect(Setup::existing_connection());
			} catch (\Throwable $e) {
				\Log::error('Setup: cannot connect with fuel/app/config/db.php: ' . $e->getMessage());

				return $this->render($configured, 'Cannot connect to the database configured in fuel/app/config/db.php. Check the credentials (or your DB_* environment variables) and that MySQL is running.', false);
			}

			if (Setup::schema_installed($pdo)) {
				Response::redirect(Uri::base());
			}
		}

		if (Input::method() !== 'POST') {
			return $this->render($configured);
		}

		try {
			$admin = $this->validate_admin();

			if (!$configured) {
				$db = Setup::validate_db_input(array(
					'host'     => Input::post('db_host'),
					'port'     => Input::post('db_port'),
					'name'     => Input::post('db_name'),
					'user'     => Input::post('db_user'),
					'password' => Input::post('db_password'),
				));

				try {
					$pdo = Setup::connect(Setup::db_config($db)['default']['connection']);
				} catch (\PDOException $e) {
					throw new \InvalidArgumentException('Could not connect to MySQL: ' . $e->getMessage());
				}

				if (Setup::schema_installed($pdo)) {
					throw new \InvalidArgumentException('That database already contains Secret Republic tables. Use an empty database.');
				}
			}

			Setup::import_schema($pdo);

			if (!$configured) {
				Setup::write_db_config($db);
				\Config::load('db', true, true);
			}

			$id = Hacker::create($admin['username'], $admin['password'], $admin['email']);
			Hacker::update(array('group' => static::ADMIN_GROUP), $id);
			Hacker::force_login($id);
		} catch (\InvalidArgumentException $e) {
			return $this->render($configured, $e->getMessage());
		} catch (\Throwable $e) {
			\Log::error('Setup failed: ' . $e->getMessage());

			return $this->render($configured, 'Setup failed: ' . $e->getMessage() . ' Empty the database and try again.');
		}

		Response::redirect(Uri::create('dashboard'));
	}

	/**
	 * @return array{username:string,password:string,email:string}
	 */
	protected function validate_admin(): array
	{
		$admin = array(
			'username' => trim((string) Input::post('admin_username')),
			'password' => (string) Input::post('admin_password'),
			'email'    => trim((string) Input::post('admin_email')),
		);

		if (!Hacker::valid_username($admin['username'])) {
			throw new \InvalidArgumentException('Admin username must be 1-30 letters or digits.');
		}
		if (!Hacker::valid_password($admin['password'])) {
			throw new \InvalidArgumentException('Admin password must be 5-30 characters long.');
		}
		if (!filter_var($admin['email'], FILTER_VALIDATE_EMAIL)) {
			throw new \InvalidArgumentException('Admin email address is not valid.');
		}

		return $admin;
	}

	protected function render(bool $configured, ?string $error = null, bool $show_form = true)
	{
		$response = Response::forge(View::forge('setup/setup', array(
			'configured' => $configured,
			'error'      => $error,
			'show_form'  => $show_form,
			'defaults'   => array(
				'db_host' => getenv('DB_HOST') ?: 'localhost',
				'db_port' => getenv('DB_PORT') ?: '3306',
				'db_name' => getenv('DB_NAME') ?: 'secretrepublic',
				'db_user' => getenv('DB_USER') ?: 'root',
			),
		)));

		if ($error !== null) {
			$response->set_status($show_form ? 422 : 503);
		}

		return $response;
	}
}

---
title: Configuration
description: Environment variables and config files for Secret Republic. Database, password salt, cron secret, session encryption key, email delivery and FuelPHP environment.
---

## Environment variables

Environment variables are the easiest way to configure a Docker deployment. Put them in a `.env` file
next to `compose.yaml`; `.env.example` lists them all.

| Variable           | Default            | Purpose |
| ------------------ | ------------------ | ------- |
| `APP_PORT`         | `8080`             | Host port for the web server (Compose only) |
| `FUEL_ENV`         | `production`       | `development` shows detailed errors; `production` shows a generic error page |
| `DB_HOST`          | `db`               | MySQL host. When set, the container writes `fuel/app/config/db.php` from the `DB_*` variables |
| `DB_PORT`          | `3306`             | MySQL port |
| `DB_NAME`          | `secretrepublic`   | Database name |
| `DB_USER`          | `secretrepublic`   | Database user |
| `DB_PASSWORD`      | `secretrepublic`   | Database password. **Change it.** |
| `DB_ROOT_PASSWORD` | `change-me-root`   | MySQL root password (Compose only). **Change it.** |
| `SR_SALT`          | `YOUR_UNIQUE_HASH2`| Global password-hashing salt. **Set once, before the first account** |
| `SR_CRON_HASH`     | `YOUR_UNIQUE_HASH` | Secret path segment for [cron URLs](../cron-jobs/) |
| `SR_CRYPT_KEY`     | random per container | 64 hex characters (`openssl rand -hex 32`) used to encrypt session cookies |

Generate strong values with:

```bash
openssl rand -hex 32   # SR_CRYPT_KEY
openssl rand -hex 16   # SR_SALT, SR_CRON_HASH, passwords
```

:::danger[Changing SR_SALT locks everyone out]
Passwords are stored as `PBKDF2-SHA256(password, salt, 10000 iterations)`. The salt is global, so
changing `SR_SALT` (or `salt` / `iterations` in `config.php`) after accounts exist makes every existing
password invalid.
:::

Without `SR_CRYPT_KEY`, Fuel generates a random session key inside the container on first boot. That is
secure, but players are logged out whenever the container is recreated. Set the variable to keep
sessions across rebuilds.

## `fuel/app/config/config.php`

| Key                           | Default | Notes |
| ----------------------------- | ------- | ----- |
| `title`                       | `Secret Republic - Cyber Security RPG` | Shown in the browser title bar |
| `email_confirmation_enabled`  | `false` | When `true`, new players get a welcome and confirmation email and a reminder on the dashboard until they confirm |
| `cron_hash`                   | `SR_CRON_HASH` or `YOUR_UNIQUE_HASH` | Use only URL-safe characters (letters, digits, `-`, `_`) |
| `salt`                        | `SR_SALT` or `YOUR_UNIQUE_HASH2` | See the warning above |
| `iterations`                  | `10000` | PBKDF2 rounds. Changing it invalidates existing passwords too |
| `locale`                      | `en_US.UTF-8` with fallbacks down to `C` | Works on minimal Docker images |
| `errors.continue_on`          | `E_DEPRECATED`, `E_USER_DEPRECATED` | PHP deprecations are logged instead of aborting the request |

## `fuel/app/config/db.php`

This file is generated. The [setup wizard](../manual-install/#3-run-the-setup-wizard) writes it for
manual installs, and the Docker entrypoint creates it from `docker/db.php` when `DB_HOST` is set.
`fuel/app/config/db.php.template` shows the format if you prefer to write it by hand:

```php
return array(
	'default' => array(
		'type' => 'pdo',
		'connection' => array(
			'dsn'      => 'mysql:host=localhost;port=3306;dbname=secretrepublic;charset=utf8mb4',
			'username' => 'secretrepublic',
			'password' => 'change-me',
		),
		'charset' => 'utf8mb4',
		'command' => "SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'",
	),
);
```

The `command` line matters. The game was written against MySQL's permissive SQL mode, and the strict
defaults of MySQL 8/9 would reject some of its inserts.

## `fuel/app/config/crypt.php`

This file is generated on the first request and is git-ignored. It holds the key Fuel uses to encrypt
session cookies, so **never commit or share it**. On Docker, set `SR_CRYPT_KEY` instead.

## Email (`fuel/app/config/email.php`)

All mail (welcome, confirmation, password reset) is queued in the `email_queue` table and sent by the
[`emails` cron job](../cron-jobs/), five messages per run. Configure SMTP in the `defaults.smtp` block:

```php
'driver' => 'smtp',
'from'   => array('email' => 'no-reply@your-domain.example', 'name' => 'Secret Republic'),
'smtp'   => array(
	'host'     => 'smtp.your-provider.example',
	'port'     => 587,
	'username' => 'apikey',
	'password' => 'secret',
	'timeout'  => 5,
	'starttls' => true,
),
```

FuelPHP also supports the `mail`, `sendmail`, `mailgun` and `mandrill` drivers.

## Third-party snippets

`public_html/assets/js/global.js` loads the original project's Google Analytics tag, and
`fuel/app/views/global/header.php` links to the original Facebook and Twitter pages. Remove them or
replace them with your own before going public.

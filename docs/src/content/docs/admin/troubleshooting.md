---
title: Troubleshooting
description: Fixes for common Secret Republic problems. Setup errors, 404s on every page, blank or 500 pages, resetting an admin password, emails not sending and voice not playing.
---

## Where are the errors?

FuelPHP writes to `fuel/app/logs/<year>/<month>/<day>.php`. In Docker:

```bash
docker compose exec app sh -c 'tail -n 50 fuel/app/logs/*/*/*.php'
```

For detailed error pages while debugging, set `FUEL_ENV=development` and restart. Don't leave it on in
production.

## Setup says it cannot connect to MySQL

- Check host, port, user and password. On Docker the host is `db`, not `localhost`.
- MySQL 8+ users created with `caching_sha2_password` work with PHP 8.3+. Very old MySQL clients aren't
  involved.
- Make sure the database exists and the user has rights on it:
  `GRANT ALL ON secretrepublic.* TO 'user'@'%';`

## "That database already contains Secret Republic tables"

The wizard only installs into an empty database. Drop the tables (or create a new database) and try
again.

## Every page except the home page is a 404

URL rewriting isn't active. Enable `mod_rewrite` and `AllowOverride All` for `public_html` on Apache, or
use the [Nginx example](../../getting-started/manual-install/#nginx-example).

## "The configured locale(s) can not be found"

The server is missing the configured locale. Current versions fall back to `C.UTF-8`/`C` automatically.
If you customized `locale` in `config.php`, add those fallbacks back.

## "Could not write fuel/app/config/db.php" or a crypt keys error

The web server user can't write to `fuel/app/config`. Fix the permissions:

```bash
chown -R www-data:www-data fuel/app/config fuel/app/cache fuel/app/logs fuel/app/tmp
```

## Reset a forgotten password

Passwords are `base64(PBKDF2-SHA256(password, salt, 10000, 32 bytes))`. Generate a hash with your salt
(the `SR_SALT` value, or `YOUR_UNIQUE_HASH2` if you never changed it) and store it:

```bash
HASH=$(docker compose exec -T app php -r 'echo base64_encode(hash_pbkdf2("sha256", "NEW-PASSWORD", getenv("SR_SALT") ?: "YOUR_UNIQUE_HASH2", 10000, 32, true));')
docker compose exec db mysql -usecretrepublic -p secretrepublic \
  -e "UPDATE user SET password = '$HASH' WHERE username = 'admin';"
```

## Everyone was logged out after a rebuild

Without `SR_CRYPT_KEY`, each new container generates its own session key. Set `SR_CRYPT_KEY` in `.env`.

## Emails are never delivered

Emails are queued, not sent immediately. Schedule the [emails cron job](../../getting-started/cron-jobs/)
and configure SMTP in `fuel/app/config/email.php`.

## Rankings are empty

Run the [rankings cron job](../../getting-started/cron-jobs/) once.

## Database (CQL) missions fail

They need PHP's `sqlite3` extension and a writable `fuel/app/tmp`. The Docker image includes both.

## No voice

Browsers block audio autoplay until the user interacts with the page. Also check that the voice is
enabled on the player's DNA page.

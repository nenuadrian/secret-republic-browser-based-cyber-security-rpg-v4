---
title: Manual install (LAMP / shared hosting)
description: Install Secret Republic on Apache, PHP 8.3+ and MySQL without Docker, from a release archive or from source, using the web setup wizard.
---

Use this path for shared hosting, MAMP/XAMPP or an existing Linux server.

## Requirements

- **PHP 8.3 or newer** (tested on 8.5) with the `pdo_mysql`, `mysqli`, `sqlite3` and `mbstring` extensions
- **Apache** with `mod_rewrite` and `AllowOverride All` (Nginx works too; see below)
- **MySQL 8.0 or newer** (tested on 9.7). MariaDB is likely to work but is not tested.
- An **empty database** and a user with full rights on it

## 1. Get the code

**Option A: release archive (no Composer needed).**
Download `secretrepublic-<version>.zip` from the
[latest release](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/releases/latest).
It already contains FuelPHP and every library. Extract it on your server.

**Option B: from source.**

```bash
git clone https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4.git
cd secret-republic-browser-based-cyber-security-rpg-v4
composer install --no-dev --optimize-autoloader
```

## 2. Point the web server at `public_html/`

The document root must be the `public_html` folder. Everything else (`fuel/`) stays outside the web
root. If your host only serves a fixed folder, upload the whole project and browse to
`http://your-host/path/public_html/`.

Make these folders writable by the web server user:

```bash
chmod -R ug+rwX fuel/app/config fuel/app/cache fuel/app/logs fuel/app/tmp
```

`fuel/app/config` must be writable during setup. The wizard writes `db.php` there, and Fuel generates
a unique `crypt.php` session key on the first request.

### Nginx example

```nginx
server {
    listen 80;
    server_name game.example.com;
    root /var/www/secretrepublic/public_html;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param FUEL_ENV production;
    }
}
```

## 3. Run the setup wizard

Open the site. Any page redirects to **`/setup`** until the game is installed. Fill in:

| Field        | Notes                                                    |
| ------------ | -------------------------------------------------------- |
| Host / port  | e.g. `localhost` / `3306`                                |
| Database     | name of the **empty** database you created               |
| User / password | MySQL credentials                                     |
| Admin account | username (letters and digits), password (5–30 chars), email |

The wizard tests the connection, imports `fuel/app/db.sql` (tables plus starter missions), writes
`fuel/app/config/db.php`, creates your admin account and logs you in. If anything fails you get a clear
error and nothing is written.

![The setup wizard](../../../../../images/setup.png)

## 4. Production settings

- Set `FUEL_ENV=production` (Apache: `SetEnv FUEL_ENV production` in `public_html/.htaccess`) so visitors
  get a generic error page instead of stack traces.
- Change `salt` and `cron_hash` in `fuel/app/config/config.php` **before** the first signup, or set the
  `SR_SALT` / `SR_CRON_HASH` environment variables. See [Configuration](../configuration/).
- Schedule the [cron jobs](../cron-jobs/).

## Re-running setup

Setup locks itself once the `user` table exists. To start over, drop all tables in the database and
delete `fuel/app/config/db.php`.

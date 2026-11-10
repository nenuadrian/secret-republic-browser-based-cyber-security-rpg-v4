---
title: Deploying to production
description: Run Secret Republic on the internet with Docker. Secrets, HTTPS with a reverse proxy, backups, upgrades, logs and the release archives.
---

## Checklist

- [ ] Strong `DB_PASSWORD` and `DB_ROOT_PASSWORD` in `.env`
- [ ] `SR_SALT` set **before** the first signup
- [ ] `SR_CRON_HASH` set, and [cron jobs](../../getting-started/cron-jobs/) scheduled
- [ ] `SR_CRYPT_KEY` set (`openssl rand -hex 32`) so sessions survive rebuilds
- [ ] `FUEL_ENV=production` (the default in `compose.yaml`)
- [ ] HTTPS in front of the app
- [ ] SMTP configured in `fuel/app/config/email.php` if you want emails
- [ ] Original Google Analytics tag and social links removed (see [Configuration](../../getting-started/configuration/#third-party-snippets))
- [ ] Database backups scheduled

## HTTPS with Caddy

The container serves plain HTTP on port 80. Put a reverse proxy in front of it. With
[Caddy](https://caddyserver.com), a `Caddyfile` like this gets and renews certificates automatically:

```text
game.example.com {
    reverse_proxy localhost:8080
}
```

To keep the game off the public interface, bind it to localhost in `.env` with `APP_PORT=127.0.0.1:8080`.

## Backups

```bash
# Dump
docker compose exec -T db sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --single-transaction "$MYSQL_DATABASE"' \
  > backup-$(date +%F).sql

# Restore
docker compose exec -T db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' < backup.sql
```

## Upgrading

```bash
git pull
docker compose up -d --build --wait
```

The database lives in the `db-data` volume and is kept across rebuilds. Read the release notes for any
schema changes.

## Logs

| Log | Command |
| --- | ------- |
| Apache access/error | `docker compose logs -f app` |
| Application (FuelPHP) | `docker compose exec app sh -c 'tail -f fuel/app/logs/*/*/*.php'` |
| MySQL | `docker compose logs -f db` |

## Without Docker

Each [GitHub release](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/releases)
includes `secretrepublic-<version>.zip` and `.tar.gz` archives with FuelPHP and all PHP libraries
included. Extract one, point the web root at `public_html/` and follow the
[manual install](../../getting-started/manual-install/) guide.

## Publishing a release (maintainers)

Push a version tag. GitHub Actions builds the archives, publishes the release and pushes versioned
Docker images.

```bash
git tag v4.1.0
git push origin v4.1.0
```

Tags with a suffix such as `v4.1.0-rc1` are published as pre-releases and don't replace _Latest_. To
build the archive locally:

```bash
composer install --no-dev --optimize-autoloader
scripts/build-release.sh v4.1.0   # writes dist/
```

---
title: Quick start with Docker
description: Run Secret Republic locally or on a server with Docker Compose. PHP 8.5, Apache and MySQL 9.7 in one command, then create your admin account in the browser.
---

The repository ships a `compose.yaml` that runs the game on **PHP 8.5 + Apache** and **MySQL 9.7 LTS**.
The database connection is configured from environment variables, so the setup wizard only asks for your
admin account.

## Requirements

- [Docker](https://docs.docker.com/get-docker/) with the Compose plugin (`docker compose version`)
- About 1 GB of free disk space

## Start the game

1. Clone the repository:

   ```bash
   git clone https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4.git
   cd secret-republic-browser-based-cyber-security-rpg-v4
   ```

2. _(Recommended)_ Create a `.env` file with your own secrets. See [Configuration](../configuration/):

   ```bash
   cp .env.example .env
   # then edit .env: set DB passwords, SR_SALT, SR_CRON_HASH and SR_CRYPT_KEY
   ```

3. Build and start the stack. `--wait` returns once both containers are healthy:

   ```bash
   docker compose up -d --build --wait
   ```

4. Open **http://localhost:8080**. You are redirected to `/setup`. Choose an admin username (letters and
   digits), a password (5–30 characters) and an email address, then press **install**.

The wizard imports the schema and the starter missions, creates your account and logs you in. The
lightning-bolt icon in the footer opens the [Cardinal admin area](../../admin/cardinal/).

:::caution[Set SR_SALT before the first signup]
Passwords are hashed with a global salt. If you set or change `SR_SALT` **after** accounts exist, every
existing password stops working. Decide on it before you run the setup wizard.
:::

## What is running

| Service | Image             | Purpose                                                        |
| ------- | ----------------- | -------------------------------------------------------------- |
| `app`   | built from `Dockerfile` (`php:8.5-apache`) | The game, served on port `8080` (`APP_PORT`) |
| `db`    | `mysql:9.7`       | Game database, stored in the `db-data` volume                  |

## Everyday commands

```bash
docker compose logs -f app           # Apache access/error log
docker compose exec app sh           # shell inside the game container
docker compose down                  # stop (data is kept)
docker compose down -v               # stop and DELETE the database volume
docker compose up -d --build --wait  # rebuild after pulling new code
```

Application logs are written to `fuel/app/logs/` inside the container:

```bash
docker compose exec app sh -c 'tail -n 50 fuel/app/logs/*/*/*.php'
```

## Use the pre-built image

Every push to `master` and every release tag publishes a multi-arch image (amd64 and arm64) to the GitHub
Container Registry:

```bash
docker pull ghcr.io/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4:latest
```

`compose.yaml` already references this image name. To use the published image instead of building
locally, run `docker compose pull && docker compose up -d`.

## Next steps

- Schedule the [cron jobs](../cron-jobs/) so that rankings update and emails are sent.
- Read [Deployment](../../admin/deployment/) before exposing the server to the internet.

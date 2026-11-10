---
title: Architecture
description: How the Secret Republic codebase is organized. FuelPHP 1.9 HMVC, directory layout, request flow, packages, models, database schema and Docker files.
---

Secret Republic is a classic server-rendered PHP application built on **FuelPHP 1.9**, an HMVC framework.
FuelPHP itself (`fuel/core`, `fuel/packages/email`, `fuel/packages/parser`) and all libraries are
installed by Composer. Only the game code is in the repository.

## Directory layout

```text
public_html/              web root: index.php front controller, .htaccess, assets (css/js/img)
fuel/
├─ app/
│  ├─ bootstrap.php       sets FUEL_ENV, boots the framework
│  ├─ config/             config.php, routes.php, email.php, db.php (generated), crypt.php (generated)
│  ├─ classes/
│  │  ├─ controller/      one class per URL segment: welcome, quests, skills, cardinal, setup, cron, …
│  │  └─ model/           game logic: missions, quests, skills, knowledge, rewards, setup, …
│  ├─ views/              PHP templates, grouped by controller
│  ├─ voice/{mp3,ogg}/    AI voice clips
│  ├─ db.sql              schema + starter content, imported by /setup
│  ├─ cache/ logs/ tmp/   runtime, must be writable (tmp holds per-run SQLite mission DBs)
├─ packages/
│  ├─ hacker/             authentication & player state (Hacker::check(), ::get(), ::can(), …)
│  ├─ messages/           flash messages, modals and voice cues stored in the session
│  ├─ globaljs/           collects inline JS from views and prints it in the footer
│  ├─ email/ parser/      FuelPHP packages (Composer-managed, git-ignored)
├─ core/ vendor/          FuelPHP core and Composer libraries (git-ignored)
docker/                   entrypoint, php.ini, env-driven db.php and crypt.php
tests/                    PHPUnit unit tests and the end-to-end smoke test
scripts/                  release packaging
docs/                     this documentation site (Astro + Starlight)
```

## Request flow

1. Apache rewrites every non-file request to `public_html/index.php`.
2. `fuel/app/bootstrap.php` sets the environment (`FUEL_ENV` from the server, `$_ENV` or `getenv`) and
   initializes Fuel with `config/config.php`, which always loads the `hacker`, `messages`, `globaljs` and
   `email` packages.
3. The router maps `/<controller>/<action>/<params…>` to `Controller_<Name>::action_<action>()`.
   `routes.php` sends `/` to `welcome/index`.
4. Controllers call static models (`\Model\Missions`, `\Model\Quests`, …) and the query builder (`\DB`),
   then render views with `View::forge()`. Most views include `global/header` and `global/footer`.
5. `Controller_Welcome` redirects to `/setup` until the database is installed (`\Model\Setup::needs_setup()`).

## Key models

| Model | Responsibility |
| ----- | -------------- |
| `Missions` | Mission runtime: preparing a run, commands, objective checks, shortcodes |
| `Quests` | Mission catalogue, availability, completion bookkeeping |
| `Task` | Timed actions (missions, cracking, training, learning) stored in `task` |
| `DbMock` | SQLite sandbox for CQL database services |
| `Cardinal` | Mission designer helpers and objective type list |
| `Skills`, `Knowledge`, `Train`, `Rewards`, `Achievements` | Progression systems |
| `Setup` | Installer: input validation, SQL import, config writing |

## Database

`fuel/app/db.sql` creates 23 InnoDB tables (`utf8mb4`) and seeds quotes, the tutorial storyline and
training content. The main groups are:

- **players**: `user`, `reward`, `task`, `user_mission`, `conversation`, `feedback`, `analytics`
- **missions**: `quest_group`, `quest`, `quest_objective`, `quest_server`, `quest_server_service`,
  `quest_service_user`, `quest_user_entity`
- **misc**: `email_queue`, `hacker_quote`, `hacker_group`, `tutorial_step`, plus a few legacy tables

Each connection runs `SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'` (see
[Configuration](../../getting-started/configuration/#fuelappconfigdbphp)).

## Error handling

FuelPHP converts PHP warnings and notices into exceptions. In `production` the player sees a generic
"Oops" page and the details go to `fuel/app/logs`. PHP **deprecations** are configured as non-fatal
(`errors.continue_on`), so newer PHP versions can't take the game down over a deprecation notice.

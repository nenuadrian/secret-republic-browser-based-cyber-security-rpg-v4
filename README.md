# Secret Republic: Browser-Based Cyber Security RPG

[![CI](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/actions/workflows/ci.yml/badge.svg)](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/actions/workflows/ci.yml)
[![Docker image](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/actions/workflows/docker.yml/badge.svg)](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/pkgs/container/secret-republic-browser-based-cyber-security-rpg-v4)
[![Docs](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/actions/workflows/docs-pages.yml/badge.svg)](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/)
[![Latest release](https://img.shields.io/github/v/release/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4?include_prereleases&sort=semver)](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/releases/latest)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

![Secret Republic](images/cover.jpg)

**Secret Republic** is an open source, browser-based **cyber security RPG**. Players break into
simulated servers, crack passwords, read intercepted mail and run SQL against live databases, guided by
an AI voice. Admins can build missions of their own in the in-game designer.

**📖 Documentation: https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/**

## Features

- **Point-and-click missions** across file/SSH, email and SQL database servers, with randomized IPs per run
- **In-game mission designer** with chained objectives, shortcodes and test play
- **Progression**: levels, six skills, knowledge trees, training, rewards and rankings
- **AI operator voice** and a mobile-ready, app-like UI
- **Modern stack**: PHP 8.5, FuelPHP 1.9 and MySQL 9.7, with one-command Docker and a web setup wizard

## Quick start

```bash
git clone https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4.git
cd secret-republic-browser-based-cyber-security-rpg-v4
docker compose up -d --build --wait
```

Open http://localhost:8080 and create your admin account. That's it.

Without Docker, download the ready-to-run archive from the
[latest release](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4/releases/latest),
upload it to any PHP 8.3+ / MySQL 8+ host and follow the
[manual install guide](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/getting-started/manual-install/).

## Documentation

| | |
| --- | --- |
| **Getting started** | [Quick start](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/getting-started/quick-start/) · [Manual install](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/getting-started/manual-install/) · [Configuration](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/getting-started/configuration/) · [Cron jobs](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/getting-started/cron-jobs/) |
| **Playing** | [Gameplay](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/game/gameplay/) · [Missions](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/game/missions/) · [Screenshots](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/game/screenshots/) |
| **Running a server** | [Admin area](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/admin/cardinal/) · [Mission designer](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/admin/mission-designer/) · [Deployment](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/admin/deployment/) · [Troubleshooting](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/admin/troubleshooting/) |
| **Development** | [Architecture](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/development/architecture/) · [Testing & CI](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/development/testing/) · [Contributing](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/development/contributing/) · [Known issues](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/development/known-issues/) |

## Screenshots

| Missions | Mission designer | Skills |
| --- | --- | --- |
| ![Mission interface](images/5.png) | ![Mission designer](images/8.png) | ![Skills](images/3.png) |

[More screenshots →](https://nenuadrian.github.io/secret-republic-browser-based-cyber-security-rpg-v4/game/screenshots/)

## Development

```bash
composer install
composer lint     # syntax + deprecation check
composer test     # unit tests
tests/smoke.sh    # end-to-end check against a running stack
```

The docs site is an Astro + Starlight project in [`docs/`](docs) (`cd docs && npm install && npm run dev`).

## History

This is version 4 of a game first launched in 2012. Read
[the journey](https://adriannenu.medium.com/secret-republic-update-hacker-themed-browser-based-php-game-855299b4cdea),
listen to the [audio trailer](https://www.youtube.com/watch?v=6thfiGb-b7c), or explore the older
[V3 codebase](https://github.com/nenuadrian/Secret-Republic-Hacking-Browser-Game-V3).

## License

[MIT](LICENSE). If you use the code or assets, please link back to this repository and contribute
improvements upstream.

---
title: Introduction
description: What Secret Republic is, what you can do with it, the technology it is built on and the state of the project.
---

Secret Republic is an open source **cyber security RPG** that runs in the browser. It is the fourth
iteration of a game that has been in development on and off since 2012. Earlier versions ran a public
competition with more than 13,000 participants.

## What's in the box

- **Missions** played through a point-and-click "operating system": ping and scan for servers, connect to
  services, crack users, decrypt, transfer and delete files, read and wipe mail, and run SQL against
  per-player databases.
- **A mission designer** (the _Cardinal_ admin area) to build servers, services, users, files, emails and
  chained objectives, and test-play them in place.
- **Progression**: levels and experience, six skills, three knowledge trees, training runs, claimable
  rewards, rankings and public profiles at `/profile/<username>`.
- **Social**: private conversations between players, a feedback inbox and world statistics.
- **Voice**: an AI operator voice (MP3/OGG clips) reacts to player actions.
- **Mobile-ready** Bootstrap UI. You can also wrap it as a native app with the companion
  [iOS](https://github.com/nenuadrian/iOS-website-elegant-rendering-swift-app) and
  [Android](https://github.com/nenuadrian/android-website-elegant-rendering) shells.

## Technology

| Layer       | Version                                                     |
| ----------- | ----------------------------------------------------------- |
| Language    | PHP 8.3 or newer; tested on **8.5**, which the Docker image uses |
| Framework   | [FuelPHP](https://fuelphp.com) 1.9 (HMVC), installed via Composer |
| Database    | MySQL 8.0+ (Docker uses **MySQL 9.7 LTS**)                   |
| Web server  | Apache with `mod_rewrite` (Docker: `php:8.5-apache`)         |
| Mission DBs | SQLite 3 (one throwaway database per mission run)            |
| Front end   | Bootstrap 3, jQuery, Font Awesome                           |

## Project status

The game is playable end to end, but it is an **alpha** and not under active feature development. The
code is kept up to date with current PHP, MySQL and Docker, and CI runs the setup wizard and the core
pages on every change. See [Known issues](../../development/known-issues/) for gameplay rough edges you may
want to fix in your own fork.

## Next steps

- [Quick start with Docker](../quick-start/), the fastest way to a running server.
- [Manual install](../manual-install/) for shared hosting or an existing LAMP stack.
- [Gameplay overview](../../game/gameplay/) for what players actually do.

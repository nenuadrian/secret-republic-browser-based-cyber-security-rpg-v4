---
title: Testing & CI
description: Lint, unit tests, the Docker smoke test and the GitHub Actions workflows that build, test, release and publish Secret Republic and its documentation.
---

## Local checks

```bash
composer install
composer lint        # php -l on every app file; fails on syntax errors AND deprecations
composer test        # PHPUnit unit tests (no database needed)
```

Unit tests live in `tests/Unit` and use a light bootstrap (`tests/bootstrap.php`) that autoloads app
models without booting the framework.

## End-to-end smoke test

`tests/smoke.sh` drives a real, freshly started stack the way a new user would:

1. `/` redirects to `/setup`
2. the wizard rejects invalid input, then installs the game
3. setup locks itself
4. the new admin logs in
5. 18 game and Cardinal pages return `200`
6. a mission starts and the mission interface renders
7. cron endpoints accept the right hash and reject a wrong one

```bash
docker compose down -v                # start from an empty database
docker compose up -d --build --wait
tests/smoke.sh http://localhost:8080
```

## GitHub Actions

| Workflow | Trigger | What it does |
| -------- | ------- | ------------ |
| `ci.yml` | push / PR to `master` | Lint + PHPUnit + `composer audit` on PHP 8.3, 8.4 and 8.5; builds the Docker stack and runs the smoke test |
| `docker.yml` | push to `master`, `v*` tags | Builds amd64 + arm64 images and pushes them to `ghcr.io` (`latest`, version and SHA tags) |
| `release.yml` | `v*` tags | Builds standalone `.zip` / `.tar.gz` archives with all dependencies, then publishes a GitHub Release (Latest, or pre-release for `-rc` style tags) |
| `docs-pages.yml` | changes under `docs/` or `images/` | Builds this site with Astro and deploys it to GitHub Pages |

## Documentation site

The docs are an [Astro](https://astro.build) + [Starlight](https://starlight.astro.build) project in
`docs/`. Pages are Markdown/MDX files in `docs/src/content/docs/`.

```bash
cd docs
npm install
npm run dev       # http://localhost:4321/secret-republic-browser-based-cyber-security-rpg-v4/
npm run build     # static site in docs/dist (includes sitemap and search index)
```

Every page needs a `title` and a descriptive `description` in its front matter. Starlight uses them for
the `<title>`, meta description, Open Graph tags and search results.

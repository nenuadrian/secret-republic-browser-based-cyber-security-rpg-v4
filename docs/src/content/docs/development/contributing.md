---
title: Contributing
description: How to contribute to Secret Republic. Development setup, coding conventions, pull requests, writing docs and sharing missions.
---

Contributions are welcome: bug fixes, new missions, docs and translations.

## Development setup

```bash
git clone https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4.git
cd secret-republic-browser-based-cyber-security-rpg-v4
composer install
FUEL_ENV=development docker compose up -d --build --wait
```

With `FUEL_ENV=development`, FuelPHP shows full error pages, and deprecations appear inline instead of
only in the log.

## Guidelines

- Target **PHP 8.3+**. `composer lint` must stay free of deprecations.
- Follow the existing style: tabs, FuelPHP naming (`Controller_Name::action_x`, `\Model\Name`), and
  views in `fuel/app/views/<controller>/`.
- Escape user input in views with `e()`.
- Keep `fuel/app/db.sql` installable on an empty MySQL 8+ database. CI imports it through the setup wizard.
- Add or update tests in `tests/Unit`, and pages in `tests/smoke.sh` for new routes.
- Update the docs in `docs/src/content/docs/` when behaviour or configuration changes.

## Pull requests

1. Fork and create a branch.
2. Run `composer lint && composer test` and, for UI or database changes, the
   [smoke test](../testing/#end-to-end-smoke-test).
3. Open a PR against `master` describing what changed and how you tested it.

## Ideas

The [known issues](../known-issues/) page lists gameplay bugs and rough edges that make good first
contributions.

## License

MIT. If you use the code or assets, please link back to the
[repository](https://github.com/nenuadrian/secret-republic-browser-based-cyber-security-rpg-v4).

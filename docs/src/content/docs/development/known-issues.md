---
title: Known issues
description: Known gameplay bugs and limitations in Secret Republic, with pointers to the code. A good starting point for contributors.
---

Secret Republic is an alpha. These issues are known in the current code. Each is a self-contained fix
and a good first contribution. Paths are relative to `fuel/app/`.

## Gameplay

| Issue | Where |
| ----- | ----- |
| The daily-login streak never goes above 1, so streak experience and all four achievements can't be earned | `packages/hacker/classes/hacker.php` → `process_login()` |
| Side objective type **Crack user** is never checked, so it can't complete | `classes/model/missions.php` |
| Skill bonuses only affect `ping`/`nmap`. The cracking key (`crack`) doesn't match the skill keys (`crack_1/2/3`) | `classes/model/missions.php` → `add_task()` |
| `required_running` with the `entityId:serviceId` form can never be satisfied | `classes/model/missions.php` |
| Rewards can be claimed more than once by re-submitting the form | `classes/controller/rewards.php`, `classes/model/rewards.php` |
| Negative skill points can be submitted | `classes/controller/skills.php` |
| Knowledge requirements (level and money) are only enforced by hiding links, and no money is deducted | `classes/model/knowledge.php` |
| `/quests/play/<id>` doesn't check level, prerequisites or live status | `classes/controller/quests.php` |
| Mission duration is minutes in play, labelled seconds in the editor, and formatted as seconds in the list | `controller/quests.php`, `views/cardinal/mission/cardinal_quest.php` |
| "Repeatable (no subsequent rewards)" missions do give rewards every time | `classes/controller/quests.php` |

## Incomplete features

- **Grid** and **Features** pages are stubs that use a `node` table which doesn't exist in `db.sql`.
- The **tutorial** is disabled. Its handler evaluates PHP stored in the database and is out of date.
- The `create-account`, `privacy-policy` and `terms-of-services` routes point at controllers or actions
  that don't exist. Use `/` and `/pages/view/...` instead.
- Guest feedback fails because `feedback.user_id` is required.
- Player groups (`hacker_group`) are only seed data; there is no UI to create or join groups.

## Security hardening ideas

- Passwords use PBKDF2 with one global salt. Moving to `password_hash()` with a migration on login would
  be a good improvement.
- The super-admin player editor writes arbitrary columns.
- Forms don't use CSRF tokens.

Fixed in the 2026 modernization: the setup wizard (credentials no longer travel in URLs, config is
written safely), committed session-encryption keys, the email cron marking the whole queue as sent, and
PHP 8.x incompatibilities.

---
title: Cron jobs
description: Schedule the Secret Republic rankings and email-queue cron endpoints with crontab or Docker so that leaderboards update and emails are delivered.
---

Two background jobs are exposed as HTTP endpoints. Both are protected by the `cron_hash` secret (see
[Configuration](../configuration/)).

| URL                                   | What it does | Suggested interval |
| ------------------------------------- | ------------ | ------------------ |
| `/cron/rankings/<cron_hash>`          | Recomputes every player's ranking points (level × 2 + skill levels + knowledge levels) and rank | every 10–60 min |
| `/cron/emails/<cron_hash>`            | Sends up to 5 queued emails (welcome, confirmation, password reset) | every 1–2 min |

A wrong hash returns `Access denied - nice try`.

## crontab

```cron
*/2 * * * *  curl -fsS https://game.example.com/cron/emails/YOUR_CRON_HASH   >/dev/null
*/15 * * * * curl -fsS https://game.example.com/cron/rankings/YOUR_CRON_HASH >/dev/null
```

## Docker

Run the same requests from the host, or from any container on the Compose network:

```cron
*/2 * * * *  curl -fsS http://localhost:8080/cron/emails/YOUR_CRON_HASH   >/dev/null
*/15 * * * * curl -fsS http://localhost:8080/cron/rankings/YOUR_CRON_HASH >/dev/null
```

:::tip
Rankings (`/rankings`) show nothing useful until the rankings job has run at least once.
:::

---
title: Cardinal admin area
description: The Cardinal admin dashboard in Secret Republic. Permission groups, promoting admins, managing players and reading feedback.
---

**Cardinal** is both the in-game AI and the name of the admin area at `/cardinal`. Players with access see
a lightning-bolt icon in the page footer.

## Permission groups

Permissions come from the `group` column of the `user` table and are defined in
`fuel/packages/hacker/classes/hacker.php`.

| `group` | Role          | Can access |
| ------- | ------------- | ---------- |
| `1`     | Player        | The game (default for new signups) |
| `3`     | Mission admin | Cardinal dashboard, mission designer, test play. **The setup wizard creates your first account in this group.** |
| `2`     | Super admin   | Everything above, plus online players, the player list and editor, feedback inbox, tutorial steps, and mission debug tools (skip timer, skip objective, debug panel) |

### Promote a player

There is no UI to grant group 2; use SQL:

```bash
# Docker
docker compose exec db mysql -usecretrepublic -p secretrepublic \
  -e "UPDATE user SET \`group\` = 2 WHERE username = 'yourname';"
```

Super admins can then change other players' fields, including `group`, at `/cardinal/player/<user_id>`, or from the name link on any player's profile.

## Pages

| URL | Group | Purpose |
| --- | ----- | ------- |
| `/cardinal` | 3, 2 | Dashboard |
| `/cardinal/missions` | 3, 2 | Mission groups and missions ([designer](../mission-designer/)) |
| `/cardinal/quest/<id>` | 3, 2 | Edit a mission |
| `/cardinal/quest_objectives/<id>` | 3, 2 | Objectives and side objectives |
| `/cardinal/quest_servers/<id>` | 3, 2 | Servers, services, users and entities |
| `/cardinal/quest_play/<id>` | 3, 2 | Test-play a mission (no rewards, ignores "live" status) |
| `/cardinal/online` | 2 | Players active in the last 10 minutes |
| `/cardinal/players` | 2 | All players, paginated |
| `/cardinal/player/<id>` | 2 | Edit any column of a player's record |
| `/cardinal/feedback` | 2 | Feedback sent from `/feedback` |
| `/cardinal/tutorial` | 2 | Tutorial steps (the tutorial is disabled by default) |

:::caution
The player editor writes raw values straight to the `user` table. Double-check before saving.
:::

The older `/cardinal/hackers` and `/cardinal/hacker/<id>` addresses redirect to the new ones.

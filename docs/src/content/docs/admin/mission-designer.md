---
title: Mission designer
description: Build your own Secret Republic missions in the browser. Mission groups, servers, services, users, files and emails, objective types, shortcodes like [ip] and [username], and BBCode formatting.
---

The mission designer lives in the [Cardinal admin area](../cardinal/) under **`/cardinal/missions`**.
Everything is edited in the browser and stored in MySQL.

## Data model

```text
quest_group            storyline shown on /quests
 └─ quest              one mission
     ├─ quest_objective          objective (ordered)
     │   └─ quest_objective      side objective (parent_objective_id)
     └─ quest_server             server (hostname, random IP per run)
         └─ quest_server_service service (port, type)
             └─ quest_service_user   user (username, password, security)
                 └─ quest_user_entity  file / program / email
```

## 1. Mission groups and missions

On `/cardinal/missions`, **add group** creates a storyline. Group fields:

| Field | Meaning |
| ----- | ------- |
| `type` | `1` = shown on `/quests`. Other values are used for training groups (for example, the decryption training group). |
| `group_order`, `name`, `description` | Ordering and text |
| `level` | Minimum player level |
| `required_quest_id` | Mission that must be completed first (`0` = none) |
| `live` | Visible to players |

**add** inside a group creates a mission. Mission fields include `name`, `type` (Normal, Daily or
Repeatable), `level`, `required_quest_id`, `summary` (HTML shown in the list), `duration`, `live`, and
the rewards `money`, `experience` and `skill_points`. `default_connection` picks a service user the player
is connected to when the mission starts.

:::caution[Duration is in minutes]
The editor labels the duration field "time (s)", but the game multiplies it by 60. A value of `100`
gives the player 100 minutes.
:::

## 2. Servers, services, users and entities

Open **servers** from the mission menu (`/cardinal/quest_servers/<id>`).

- **Server**: `hostname`, `discovered` (visible from the start) and `hide_hn` (show "unknown hostname").
  Each run assigns a random IP.
- **Service**: `port`, `type`, `discovered`, `welcome` (message on connect) and `required_objective`
  (hidden from `nmap` until that objective is active).

  | Type | Name | Entities |
  | ---- | ---- | -------- |
  | 1 | FILES / SSH | files: `1` text, `2` code, `3` executable |
  | 2 | EMAIL / CMTP | mail: `1` received, `2` sent |
  | 3 | DB / CQL | none. The user's `content` holds an SQL script that seeds a private SQLite database. |

- **User**: `username` (an email address for mail services), `password` and `security` (seconds needed
  to crack it). A password with security `0` cannot be cracked.
- **Entity**: `title`, `content`, `entity_order`, `security` (greater than 0 means encrypted, and is also
  the crack time), `password` (file decryption), `sender_receiver` (mail only), `required_objective`, and
  `required_running` (executables only: comma-separated entity IDs that must be running first).

## 3. Objectives

Open **objectives** (`/cardinal/quest_objectives/<id>`). Each objective has an order, a `name` and a
`story` shown to the player. Its **side objectives** are the checks that complete it:

| ID | Type | Data | Completed when the player… |
| -- | ---- | ---- | -------------------------- |
| 1 | Connect 2 user | user | connects to that service user |
| 3 | Delete entity | entity | erases the file or email |
| 4 | Execute entity | entity | runs the executable |
| 7 | Execute entity on user | entity + user | runs the executable while connected as that user |
| 5 | Open entity | entity | opens or decrypts it |
| 6 | Transfer entity | entity + target user | transfers the file to that user |
| 8 | SQL | user + `SQL\|expected` | after any query, `SQL` returns `expected` on that user's database |

Example SQL check: `SELECT count(*) FROM company|3`.

The objective is done when all **non-optional** side objectives are done. Optional side objectives can
only be flagged in the database (`quest_objective.optional = 1`).

:::note
Type `2 · Crack user` appears in the list but is never checked by the game, so it can't complete an
objective. Use _Connect 2 user_ after the crack instead.
:::

## 4. Dynamic text: shortcodes

Hostnames, service welcome messages, usernames, entity titles and content, and objective names, stories
and data are processed with these shortcodes when a mission starts or an objective activates:

| Shortcode | Output |
| --------- | ------ |
| `[username]` | The player's username |
| `[user attr="level"]` | A column from the player's record (`level`, `money`, …) |
| `[ip hn="demo.server"]` or `[ip id=12]` | The run's random IP of that server, rendered as click-to-select text |
| `[server hn="demo.server" attr="hostname"]` | Any field of that mission server, including `ip` |
| `[code]rm -rf /[/code]` | Monospace, click-to-select command |
| `[substring start=0 length=4]text[/substring]` | Part of a string |

After shortcodes, text is formatted with **BBCode** ([jBBCode](https://github.com/jbowens/jBBCode)
defaults): `[b]`, `[i]`, `[u]`, `[color=#209eff]…[/color]`, `[url]…[/url]` / `[url=…]…[/url]` and
`[img]…[/img]`.

```text
Take a look at [ip hn="demo.server"].
The password for the SSH service is [b]root[/b].
Then run [code]nmap[/code] and connect to what you find, [username].
```

:::tip
Always pass `hn` or `id` to `[ip]` and `[server]`. A hostname that doesn't match falls back to the last
server in the mission.
:::

## 5. Test it

Choose **play** in the mission menu (`/cardinal/quest_play/<id>`) to run the mission as a test. Test runs
ignore `live`, last a long time and give no rewards. Super admins (group 2) also get **skip objective**
and **debug** buttons in the mission interface.

When it works, set the group and mission to `live`.

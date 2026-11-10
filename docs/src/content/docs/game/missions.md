---
title: How missions work
description: Secret Republic missions explained. Servers, file/SSH, email and SQL database services, the ping, nmap, connect and crack commands, objectives, timers and rewards.
---

A mission drops you into a small simulated network. Each run gets **freshly randomized IP addresses**,
so solutions can't simply be copied between players.

## Anatomy of a mission

```text
Mission
 ├─ Objectives (in order)            "Connect to the server", "Delete the log", ...
 │   └─ Side objectives (the actual checks: connect, open, delete, execute, transfer, SQL)
 └─ Servers (hostname + random IP)
     └─ Services (port + type)
         └─ Users (username, password, security level)
             └─ Entities (files, programs, emails)
```

You see one objective at a time. Its **story** opens in a dialog and you can re-open it from the
_objective_ link. When every required side objective is done, the next objective appears. After the last
one the mission is complete.

## Commands

| Command | Effect |
| ------- | ------ |
| **ping** `<ip>` | Discovers the server that owns that IP. IPs usually appear in the story or in files. |
| **nmap** | Lists the services on a discovered server. Some services stay hidden until a certain objective is reached. |
| **rename** | Gives a discovered server a label of your own. |
| **connect** | Logs in to a service user with its password, or directly if the user isn't protected. |
| **crack** | Brute-forces a user. Takes as many seconds as the user's security level, then removes the password. |
| **disconnect** | Leaves the current service. |

## Service types

### Files (SSH)

A file system with **text files**, **code** and **executables**. Encrypted files must be **decrypted**
(with their password or by cracking, which takes time) before you can read them. You can also **erase**
files, **transfer** them to another discovered file service (you need that user's password), and
**execute** or **kill** programs.

### Email (CMTP)

The _Cardinal Mail Transfer Protocol_: an inbox and a sent folder. Read messages for clues, erase them,
and crack encrypted ones.

### Database (CQL)

The _Cardinal Query Language_ is SQL. Each database user gets a private SQLite database for the
duration of the run, seeded by the mission designer. `SELECT`, `INSERT`, `UPDATE` and `DELETE` are
allowed; results are shown as a table. Objectives can require the data to reach a specific state.

## Timer, failure and rewards

- Every mission has a **time limit** (shown as a ring countdown). When it expires, the mission ends without
  a reward.
- You can **cancel** a running mission at any time.
- On success you receive the mission's money, experience and skill points as a **reward** to claim.
- **Normal** missions can be completed once, **daily** missions again after 24 hours, and **repeatable**
  missions any time.

## Starter content

The database ships with a short tutorial storyline, _Computer Science Flash Course_:

1. **Newborns**: connect over SSH, delete a file, ping and scan, transfer a file, crack a root account.
2. **You've got mail**: the CMTP email service.
3. **CQL**: query and modify a company database.

A **Decryption training** group powers the training feature. Build more with the
[mission designer](../../admin/mission-designer/).

---
title: Gameplay overview
description: How Secret Republic plays. Missions, levels and experience, skills, knowledge, training, rewards, rankings, conversations and the AI voice in the browser-based cyber security RPG.
---

Players are new citizens of the Secret Republic, recruited by the **Cardinal**, an AI that runs the
in-game operating system and speaks to you. You register, read the story, and start taking
[missions](../missions/).

## Core loop

1. **Accept a mission** from `/quests`. Missions are grouped into storylines and unlock by level and by
   completing earlier missions.
2. **Solve it** in the mission interface before the timer runs out.
3. **Claim the reward** (money, experience and skill points) on the rewards page.
4. **Spend skill points** and study **knowledge** to get faster and unlock harder content.

## Systems

| System | Where | How it works |
| ------ | ----- | ------------ |
| Levels & experience | header, dashboard | Level *n* needs *(n + 1) × 10* experience. Experience comes from rewards. |
| Money | header (cube icon) | Earned from missions and training. |
| [Missions](../missions/) | `/quests` | Normal (once), daily (every 24 h) or repeatable. One mission runs at a time. |
| Skills | `/skills` | Six skills: Parallel programming, Vulnerability analysis, Network security, Database, Firewall cracking and Cryptography. Each skill point adds one skill experience. |
| Knowledge | `/knowledge` | Three trees: Reconnaissance, Cryptology and Stealth. Studying takes 5 minutes and grants skill experience. |
| Training | `/train` | A random "Decryption" training run (about 17 minutes) that awards experience, money, a skill point and training experience, then has a 6-hour cooldown. |
| Rewards | `/rewards` | Every completed mission or training run creates a reward you claim manually. The header shows unclaimed rewards. |
| Rankings | `/rankings` | Global leaderboard, recomputed by the [rankings cron job](../../getting-started/cron-jobs/). |
| Profiles | `/hacker/access/<username>` | Public profile with level, rank and achievements. |
| Conversations | `/conversations` | Private one-to-one messages with BBCode formatting and an unread counter. |
| DNA | `/dna` | Personal settings: toggle the AI voice and change your password. |
| World | `/world` | Global statistics for the server. |
| Feedback | `/feedback` | Players send feedback that super admins read in Cardinal. |

## The AI voice

Many actions trigger a spoken line from the Cardinal, such as _"access granted"_, _"cracking initiated"_
or _"command done"_. The clips live in `fuel/app/voice/{mp3,ogg}`. Players can switch the voice off on the
DNA page. Browsers may block autoplay until the player has interacted with the page.

## Mobile

The UI is responsive and built to feel like an app. To publish it as a native app, wrap the site with
the companion [iOS](https://github.com/nenuadrian/iOS-website-elegant-rendering-swift-app) or
[Android](https://github.com/nenuadrian/android-website-elegant-rendering) shell. The voice player
already talks to their native audio bridges.

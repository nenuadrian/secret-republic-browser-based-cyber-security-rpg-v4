#!/usr/bin/env bash
# End-to-end smoke test against a freshly started stack (docker compose up).
# Runs the web setup wizard, logs in as the new admin and checks key pages.
#
#   tests/smoke.sh [base-url]        (default http://localhost:8080)
set -euo pipefail

BASE="${1:-http://localhost:8080}"
JAR="$(mktemp)"
BODY="$(mktemp)"
trap 'rm -f "$JAR" "$BODY"' EXIT

ADMIN_USER="smokeadmin"
ADMIN_PASS="$(od -An -N10 -tx1 /dev/urandom | tr -d ' \n')"
ADMIN_EMAIL="smoke@example.test"

fail() { echo "FAIL: $*" >&2; exit 1; }

# Prints "<status> <redirect-url>" for a request
req() { curl -s -o "$BODY" -c "$JAR" -b "$JAR" -w '%{http_code} %{redirect_url}\n' "$@"; }

echo "1. fresh install redirects to /setup"
read -r code location < <(req "$BASE/")
[[ "$code" == 302 && "$location" == */setup ]] || fail "expected 302 -> /setup, got $code $location"

echo "2. setup wizard renders"
read -r code _ < <(req "$BASE/setup")
[[ "$code" == 200 ]] && grep -q 'Admin account' "$BODY" || fail "setup page: $code"

echo "3. setup rejects an invalid admin"
read -r code _ < <(req --data-urlencode "admin_username=bad name" --data-urlencode "admin_password=x" --data-urlencode "admin_email=nope" "$BASE/setup")
[[ "$code" == 422 ]] || fail "expected 422 for invalid admin, got $code"

echo "4. setup installs the game"
read -r code location < <(req --data-urlencode "admin_username=$ADMIN_USER" --data-urlencode "admin_password=$ADMIN_PASS" --data-urlencode "admin_email=$ADMIN_EMAIL" "$BASE/setup")
[[ "$code" == 302 && "$location" == */dashboard ]] || { cat "$BODY" | head -50; fail "setup POST: $code $location"; }

echo "5. setup is locked afterwards"
read -r code _ < <(curl -s -o /dev/null -w '%{http_code} %{redirect_url}\n' "$BASE/setup")
[[ "$code" == 302 ]] || fail "setup still reachable after install: $code"

echo "6. admin can log in"
rm -f "$JAR"
read -r code location < <(req --data-urlencode "username=$ADMIN_USER" --data-urlencode "password=$ADMIN_PASS" -d "connect=true" "$BASE/")
[[ "$code" == 302 && "$location" == */dashboard ]] || fail "login: $code $location"

echo "7. game pages respond"
for path in dashboard quests quests/group/5 skills knowledge train rewards rankings world conversations dna \
            profile/$ADMIN_USER pages/view/story cardinal cardinal/missions cardinal/quest/9 \
            cardinal/quest_objectives/9 cardinal/quest_servers/9; do
	read -r code _ < <(req "$BASE/$path")
	[[ "$code" == 200 ]] || fail "/$path returned $code"
	printf '   %-28s %s\n' "/$path" "$code"
done

echo "8. profile URLs"
read -r code location < <(req "$BASE/profile")
[[ "$code" == 302 && "$location" == */profile/$ADMIN_USER ]] || fail "/profile should open your own profile: $code $location"
read -r code location < <(req "$BASE/hacker/access/$ADMIN_USER")
[[ "$code" == 301 && "$location" == */profile/$ADMIN_USER ]] || fail "legacy profile URL: $code $location"
read -r code _ < <(req "$BASE/profile/no-such-player-xyz")
[[ "$code" == 404 ]] || fail "unknown profile should be 404, got $code"
read -r code location < <(req "$BASE/cardinal/hackers")
[[ "$code" == 301 && "$location" == */cardinal/players ]] || fail "legacy admin URL: $code $location"

echo "9. starting a mission opens the mission interface"
read -r code location < <(req "$BASE/quests/play/9")
[[ "$code" == 302 && "$location" == */quests/interface ]] || fail "quests/play/9: $code $location"
read -r code _ < <(req "$BASE/quests/interface")
[[ "$code" == 200 ]] && grep -q 'cancel mission' "$BODY" || fail "mission interface: $code"
req -d "cancel=true" "$BASE/quests/interface" >/dev/null

echo "10. cron endpoints"
read -r code _ < <(req "$BASE/cron/rankings/${SR_CRON_HASH:-YOUR_UNIQUE_HASH}")
[[ "$code" == 200 ]] || fail "cron rankings: $code"
read -r code _ < <(req "$BASE/cron/rankings/wrong-hash")
grep -q 'Access denied' "$BODY" || fail "cron accepted a wrong hash"

echo "OK - smoke test passed"

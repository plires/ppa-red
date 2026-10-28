# Lead attribution

## Objective

Store the marketing origin of every lead submitted from the landing so spend can
later be related to lead → partner → sale, and so leads can be reported back to
Google Ads / Meta conversion APIs in the future.

## Problem

`form_submissions` stores geography, assigned partner, status and contact data,
but nothing about where the lead came from (no click IDs, no UTMs, no landing or
referrer). Campaign measurement cannot be joined to leads.

## Scope

- Nullable columns on `form_submissions`: `gclid`, `gbraid`, `wbraid`, `fbclid`,
  `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`,
  `landing_url`, `referrer`.
- Browser capture: on landing load, read click IDs / UTMs from the URL and keep
  them in localStorage for 90 days (last non-direct touch wins), so a visitor who
  comes back later without params is still attributed. Sent with the form.
- Backend stores them sanitized. Province, locality and assigned partner are
  already stored.

## Constraints

- Attribution must never block a lead: invalid or oversized values are truncated
  or dropped, never a validation error.
- No over-engineering: plain columns, no separate table, no dashboard UI yet.
- No commits unless the user asks.

## Tasks

- [x] T1 — Migration, model, controller sanitization + Pest tests; `attribution.js` capture + node tests; wire into `Landing.jsx` (route: delegated, writer trigger: 6+ non-trivial files)

## Acceptance criteria

- A submission posted with gclid/UTMs persists them.
- A submission without attribution still succeeds with nulls.
- Oversized / non-string attribution values do not reject the lead.
- Visiting `/?utm_source=x&gclid=y` then submitting stores those values.

## Checks

- `docker compose -f docker-compose.local.yml exec app ./vendor/bin/pest`
- `npm run test:js`
- Browser: submit from a URL with params and verify the row in MySQL.

## Progress

- Exploration done (2026-10-28): schema and store flow mapped.
- T1 implemented (2026-10-28, uncommitted). RED: 3/4 new Pest tests failed
  (missing columns / `ATTRIBUTION_FIELDS`); node test failed (missing module).
  GREEN: `pest` 366 passed; `npm run -s test:js` 31 passed; `pint --test` pass;
  dev DB migrated.
- Browser acceptance passed (2026-10-28): visited `/?utm_source=google&utm_medium=cpc&utm_campaign=portones_cordoba&gclid=TEST-GCLID-123`,
  revisited `/` without params, submitted → row 576 stored gclid, utm_* and
  landing_url. Prod entrypoint runs `migrate --force`, so deploy needs no manual step.

## Next step

Commit T1 when the user asks (no commits without explicit request).

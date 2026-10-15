# Purge test data before launch

## Objective

Provide an artisan command that removes the data created during the production
test phase (partners, their locality assignments, form submissions and related
records) while keeping the catalogs the system needs to run.

## Problem

Production currently holds test partners, locality assignments and form
submissions. `migrate:fresh --seed` would also wipe catalogs edited in
production (transactional emails, geography) and re-create a seeded admin with
a password that lives in the repository.

## Scope

Deletes:
- `form_submission_notifications`, `form_responses`, `form_submissions`
  (including soft-deleted rows).
- Every user with role `partner`, including soft-deleted ones, plus their
  `sessions`, `password_reset_tokens` and `personal_access_tokens`.
- Queue leftovers: `jobs` and `failed_jobs`.

Keeps:
- Admin users.
- `form_submission_statuses`, `transactional_emails`.
- Provinces, zones and localities. Localities are detached from partners
  (`user_id = null`), never deleted.

## Constraints

- `localities.user_id` has `ON DELETE CASCADE`: localities must be detached
  before partners are hard-deleted, or MySQL deletes the localities.
- `form_submission_notifications.form_submission_id` has no cascade: delete
  notifications before submissions.
- Most models use `SoftDeletes`: purge must bypass soft deletes.
- Runs inside one transaction; prints counts and asks for confirmation unless
  `--force` is passed.

## Tasks

- [x] T1 — `app:purge-test-data` command with feature tests (route: delegated, writer trigger: command + test are 2 non-trivial files)

## Acceptance criteria

- After running, no partners, submissions, responses or notifications remain.
- Admins, statuses, transactional emails, provinces, zones and localities
  remain; localities have `user_id = null`.
- Declining the confirmation changes nothing.

## Checks

- `docker compose -f docker-compose.local.yml exec app ./vendor/bin/pest`
- `docker compose -f docker-compose.local.yml exec app ./vendor/bin/pint --test`

## Progress

- Branch: `feat/purge-test-data`
- T1 done. RED: 5 failed (`app:purge-test-data` not found). GREEN: 5 passed
  (50 assertions). Full suite: 357 passed. `pint --test`: pass.
- Removing the locality detach step makes 3 tests fail: SQLite enforces the
  cascade too, so the guard is covered.
- Dry run on local MySQL without `--force`: counts printed, default answer
  aborted, no rows changed.
- If there is nothing to purge, the command exits without prompting.

## Next step

Back up production (`mysqldump`), then run
`php artisan app:purge-test-data` inside the production container.

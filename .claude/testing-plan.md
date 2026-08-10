# Testing Plan — PPA RED

Durable coverage inventory for the pre-production test suite. Update the status
column as batches land. This file is the source of truth for what is covered and
what is still open, so the work survives across sessions.

## Conventions

- Framework: Pest, `RefreshDatabase` on every Feature test (`tests/Pest.php`).
- Database: SQLite `:memory:`, forced in `tests/TestCase.php::createApplication()`.
  See the incident note there — the Docker container injects real `DB_*` env vars
  that override `phpunit.xml`, so the `config()` override must stay.
- Factories must be deterministic. Never use `inRandomOrder()` in a factory.
- Helpers live in `tests/Pest.php`: `admin()`, `partner()`, `seedStatuses()`,
  `statusId()`, `localityWithPartner()`, `localityWithoutPartner()`.
- Assert behaviour (DB state, dispatched jobs, redirects, authorization), not
  implementation details.

## Batches

| # | Area | Scope | Status |
|---|------|-------|--------|
| 1 | Foundations | Deterministic factories, test helpers, status seeding | done |
| 2 | Public intake | Landing form submission, token page, public replies | done |
| 3 | Authorization | `AdminMiddleware` over admin-only routes, partner scoping | done |
| 4 | Submission lifecycle | Status changes, responses, partner reassignment, closure command | done |
| 5 | Notifications & jobs | 8 jobs dispatch to the right recipient | done |
| 6 | Geographic CRUD | Provinces / zones / localities, partners, admin users | done |
| 7 | Reports | Aggregations, null `user_id` regression | done |
| 8 | Public API | 4 geographic endpoints feeding the landing form | done |
| 9 | Notifications | Read state for notifications and comments | done |

Suite total: 296 tests, 1008 assertions, ~4s. Verified stable across repeated runs.

## Covered behaviour

### Batch 2 — Public intake (`tests/Feature/Public/`)
- Submission is created with the locality's partner as owner.
- Submission with an unassigned locality notifies the admin instead.
- First `FormResponse` mirrors the message and is not a system message.
- Confirmation email is always dispatched to the requester.
- Validation rejects missing/invalid fields and unknown foreign keys.
- `secure_token` is generated, unique, and hidden from serialization.
- Token page renders, unknown token renders `NotFound` (no 500, no leak).
- Closed submissions are flagged `isClosed` / `closedByPartner`.

### Batch 3 — Authorization (`tests/Feature/Authorization/`)
- Guests are redirected to login on every dashboard route.
- Partners are redirected away from every admin-only route.
- Admins reach admin-only routes.
- Partner dashboard root redirects to the submissions index.

### Batch 4 — Lifecycle (`tests/Feature/Lifecycle/`)
- Partner reply moves status to "Respondido Por El Partner".
- Public reply moves status back to pending.
- Reassignment is admin-only and re-points the submission.
- `UpdateFormSubmissionStatus` 48h delay + 7 day closures.

### Batch 5 — Jobs (`tests/Feature/Jobs/`)
- Each job dispatches the expected mailable to the expected address.

### Batch 6 — Geographic CRUD (`tests/Feature/Geography/`)
- Create / update / delete / restore for provinces, zones, localities.
- A locality may be left without a partner.

### Batch 7 — Reports (`tests/Feature/Reports/`)
- Aggregations return expected shape.
- Submissions with `user_id = null` do not break the partner report.

## Defects found while writing the suite — all fixed

Each one is now covered by a test asserting the corrected behaviour.

1. **`form_submissions.edit` and `form_submissions.destroy` returned 500.**
   The routes pointed at `FormSubmissionController::edit()` and `::destroy()`,
   which do not exist. Nothing in `resources/` referenced them, so both routes
   were removed from `routes/web.php` rather than inventing a delete feature.
2. **Duplicate zone name returned 500 instead of a validation error.**
   `ZoneRequest` now carries `unique:zones,name,{id}`, matching what
   `ProvinceRequest` already did. The rule does not exclude soft-deleted zones
   because the database unique index does not either.
3. **A partner could mark another partner's notification as read.**
   `FormNotificationController::markAsReadAndRedirect()` now checks ownership
   and answers 404 (not 403, so it does not confirm the notification exists).
   Admins still reach any notification.
4. **Report endpoints leaked partner data — worse than first reported.**
   The three routes shared with partners sit outside `AdminMiddleware`. Beyond
   the aggregate totals, `getFormulariosByStatus` honoured the `user_id` in the
   URL, so a partner could read the DETAIL of another partner's submissions,
   end-user name included — and `user_id=null` returned the whole system.
   `statusChart` also handed a partner the full partner directory.
   All three now scope to the authenticated partner and ignore the incoming
   filter; admin behaviour is unchanged.
5. **No geographic consistency validation on the public form.**
   `PublicFormSubmissionController::store()` now rejects a `locality_id` that
   does not belong to the submitted `province_id`, and a `zone_id` that is not
   exactly the locality's zone. Verified compatible with the landing form,
   which chains province → zone → locality.

## Known gaps / follow-ups

- `RegionController`, `DistrictController`, `Region`, `District` and the
  migrations under `database/migrations/carpeta sin título/` are unreachable:
  no routes point at them and that migration subdirectory is not loaded by the
  default migrator, so those tables do not exist. Dead code — untested on
  purpose. Candidate for removal.
- `PublicFormSubmissionController::store` validates inline instead of using the
  existing `FormSubmissionRequest`.
- `FormResponseRequest` forces `is_system = 1` on partner replies, so a real
  partner message is stored flagged as a system message. It works today because
  the unread counters deliberately skip system messages, but the naming is
  misleading and any future feature reading `is_system` will get it wrong.
- Frontend (React/Inertia pages) has no test coverage. Out of scope for this
  suite, which covers the backend contract only.

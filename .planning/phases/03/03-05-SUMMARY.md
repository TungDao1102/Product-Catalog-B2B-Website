---
phase: 03
plan: 05
type: execute
subsystem: customer-facing
tags: ["contact-form", "quote-modal", "email", "navbar", "tests"]
requires: ["03-01", "03-04"]
provides: ["contact-form-backend", "quote-request-backend", "email-notification-flow", "navbar-links"]
affects: ["Contact", "Inquiry", "AdminNotification", "CustomerConfirmation"]
tech-stack:
  added: ["ContactController", "InquiryController"]
  patterns: ["Controller store with validation + DB create + email notify pattern"]
key-files:
  created:
    - app/Http/Controllers/ContactController.php
    - app/Http/Controllers/InquiryController.php
    - tests/Feature/Http/ContactFormTest.php
    - tests/Feature/Http/InquiryFormTest.php
    - tests/Feature/Http/NavbarTest.php
    - tests/Feature/MailTest.php
    - tests/Feature/SeederTest.php
  modified:
    - resources/views/contact.blade.php
    - resources/views/products/show.blade.php
    - resources/views/partials/navbar.blade.php
    - routes/web.php
    - tests/Feature/Http/ContactFormTest.php
decisions:
  - "Standard POST form submission for quote modal (no AJAX) with session flash re-open"
  - "Email sent synchronously with try-catch per D-16; mail failure never blocks user response"
  - "Related products quote buttons link to product detail page (where modal lives)"
  - "Contact form action uses route('contact') — same URL works for both GET/POST via method matching"
metrics:
  duration: "~30 minutes"
  completed: "2026-06-28"
status: complete
---

# Phase 3 Plan 05: Contact Form, Quote Modal, Navbar Links, Email Integration, and Comprehensive Tests

**One-liner:** Implemented ContactController + InquiryController with DB persistence, email notifications (AdminNotification + CustomerConfirmation), updated contact form (D-09), quote modal on product detail page (D-06), navbar links for Tin tức and Dự án (D-23), and 18 passing tests across 5 test files.

## Tasks Completed

| Task | Name | Commit | Files |
|------|------|--------|-------|
| 1 | Create ContactController with show/store, update contact form, routes | `d5ecd27` | `app/Http/Controllers/ContactController.php`, `resources/views/contact.blade.php`, `routes/web.php` |
| 2 | Create InquiryController with store, add quote modal to product page | `c0f2d62` | `app/Http/Controllers/InquiryController.php`, `resources/views/products/show.blade.php` |
| 3 | Update navbar with Tin tức/Dự án links, create 5 test files | `0111bcf` | `resources/views/partials/navbar.blade.php`, 5 test files |
| — | Fix assertSee escaping in ContactFormTest | `707f9c2` | `tests/Feature/Http/ContactFormTest.php` |

## Implementation Details

### Task 1: ContactController & Contact Form (REQ-07)

**ContactController** (`show()` + `store()`):
- `show()` renders the contact view with Google Maps embed (D-10 preserved)
- `store()` validates name*, email*, phone, company, message fields
- Removed subject field (D-09), added phone + company fields
- Creates `Contact` record with `is_read=false`
- Sends AdminNotification + CustomerConfirmation emails with try-catch
- Form title changed from "Gửi yêu cầu báo giá" to "Gửi liên hệ"
- Added `@error` handling with `is-invalid` classes and `old()` values
- Added success flash display with dismissible alert

**Routes:**
- `GET /lien-he` → `ContactController@show` (name: `contact`)
- `POST /lien-he` → `ContactController@store` (name: `contact.store`)

### Task 2: InquiryController & Quote Modal (REQ-08)

**InquiryController** (`store()`):
- Validates product_id* (exists:products), name*, email*, phone, company, quantity, message*
- Creates `Inquiry` record with `is_read=false`
- Sends AdminNotification + CustomerConfirmation emails with try-catch
- Redirects back with `quote_success` flash

**Quote Modal on product detail page:**
- Main quote button changed from `<a href="{{ route('contact') }}?product=...">` to `<button data-bs-toggle="modal" data-bs-target="#quoteModal">`
- Bootstrap 5 modal with `modal-lg`, includes hidden `product_id` input
- Form fields: name, email, phone, company, quantity, message in responsive grid
- Validation errors displayed via `$errors` bag with `is-invalid` classes
- Success flash shown as dismissible alert
- Modal re-opens automatically on validation error or success via `@push('scripts')` DOMContentLoaded handler
- Related products quote buttons link to product detail page (where modal lives)

**Route:**
- `POST /yeu-cau-bao-gia` → `InquiryController@store` (name: `inquiries.store`)

### Task 3: Navbar & Tests (REQ-12, REQ-07-11)

**Navbar update (D-23):**
- Added "Tin tức" link after "Danh mục" dropdown: `route('posts.index')`
- Added "Dự án" link after "Tin tức": `route('projects.index')`
- Active route detection using `request()->routeIs('posts.*')` and `request()->routeIs('projects.*')` pattern

**Test Files (18 tests, 43 assertions, all green):**

| Test File | Tests | Coverage |
|-----------|-------|----------|
| `ContactFormTest` | 6 | Page renders, no subject field, phone+company fields, validation, DB record, success redirect (REQ-07) |
| `InquiryFormTest` | 3 | Validation, DB record with product_id, redirect back with quote_success (REQ-08) |
| `MailTest` | 5 | AdminNotification for inquiry/contact, CustomerConfirmation for inquiry/contact, mailable renders (REQ-09, REQ-10) |
| `SeederTest` | 2 | PostSeeder creates ≥3 posts, ProjectSeeder creates ≥3 projects (REQ-11) |
| `NavbarTest` | 2 | Tin tức link visible, Dự án link visible (REQ-12) |

### Email Integration (REQ-09, REQ-10)

Both controllers follow the same email pattern:
1. `AdminNotification` sent to `config('mail.from.address')` — notifies admin of new submission
2. `CustomerConfirmation` sent to submitter's email — confirms receipt
3. Both use synchronous `send()` (D-16), wrapped in try-catch (per Research Pitfall 5)
4. On failure, error is logged via `Log::error()` and user still gets success redirect

Existing Mailable classes (`AdminNotification`, `CustomerConfirmation`) and templates (`admin-notification.blade.php`, `customer-confirmation.blade.php`) were already created by prior plans and required no changes.

## Deviations from Plan

No deviations — plan executed exactly as written.

### Auto-fixed Issues

**1. [Rule 1 - Bug] assertSee escaping for HTML attribute assertions**
- **Found during:** Task 3 verification
- **Issue:** `assertSee('name="phone"')` with default escaping (`$escaped=true`) searches for HTML-escaped `name=&amp;quot;phone&amp;quot;` instead of literal `name="phone"`
- **Fix:** Added `false` as second parameter to `assertSee` and `assertDontSee` calls that check HTML attribute values
- **Files modified:** `tests/Feature/Http/ContactFormTest.php`
- **Commit:** `707f9c2`

## Threat Surface Scan

No new threat surface introduced beyond what's documented in the plan's threat model. All mitigations verified:
- **T-03-09 (CSRF):** `@csrf` on both forms
- **T-03-10 (Validation):** `$request->validate()` with specific rules
- **T-03-11 (SQL Injection):** Eloquent `::create()` parameterized queries
- **T-03-12 (Email Header Injection):** Laravel Mail facade sanitizes headers
- **T-03-13 (DoS via email failure):** try-catch wrapping Mail::send()
- **T-03-14 (Email disclosure):** Accepted — customer enters own email

## Verification Results

```bash
php artisan test --filter="ContactFormTest|InquiryFormTest|MailTest|SeederTest|NavbarTest"
# Results: 18 passed, 43 assertions
# Duration: ~20s
```

## Success Criteria Checklist

- [x] ContactController with show/store created
- [x] Contact form updated per D-09: subject removed, phone+company added, Google Maps preserved (D-10)
- [x] InquiryController with store created
- [x] Quote modal on product detail page per D-06
- [x] Modal form posts via standard POST, re-opens on validation error via session flash
- [x] Email sent synchronously for both Inquiry and Contact with try-catch error handling
- [x] Navbar updated with Tin tức and Dự án links (D-23)
- [x] All 5 test files pass (ContactForm: 6, InquiryForm: 3, Mail: 5, Seeder: 2, Navbar: 2 = 18 tests)
- [x] Each task committed individually

## Self-Check: PASSED

All 11 created/modified files verified on disk. All 4 commits verified in git log. All 18 tests passing.

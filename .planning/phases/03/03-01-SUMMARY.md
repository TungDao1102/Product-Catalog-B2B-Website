---
phase: 03
plan: 01
subsystem: "Foundation — Models, Mailables, Seeders"
tags:
  - models
  - mailables
  - email
  - seeders
  - eloquent
requires:
  - Phase 1 (Laravel + DB + Filament)
  - Phase 2 (Product model for Inquiry relationship)
provides:
  - Post, Project, Contact, Inquiry models
  - AdminNotification, CustomerConfirmation Mailables
  - 2 Blade email templates
  - PostSeeder, ProjectSeeder
affects: []
tech-stack:
  added: []
  patterns:
    - "PHP 8.4 #[Fillable] attribute on Eloquent models"
    - "Mailable classes with envelope()/content() pattern"
    - "Synchronous email sending (no queue per D-16)"
    - "Seeder: WithoutModelEvents + direct create() with hardcoded arrays"
key-files:
  created:
    - app/Mail/AdminNotification.php
    - app/Mail/CustomerConfirmation.php
    - resources/views/mail/admin-notification.blade.php
    - resources/views/mail/customer-confirmation.blade.php
    - database/seeders/PostSeeder.php
    - database/seeders/ProjectSeeder.php
  modified:
    - app/Models/Post.php
    - app/Models/Project.php
    - app/Models/Contact.php
    - app/Models/Inquiry.php
    - database/seeders/DatabaseSeeder.php
decisions: []
metrics:
  duration: "~15 min"
  completed_date: "2026-06-28"
status: complete
---

# Phase 3 Plan 01: Foundation — Models, Mailables, Seeders

**One-liner:** Established 4 Eloquent models with PHP 8.4 #[Fillable] attributes and casts, 2 Mailable classes with Blade email templates, and 2 seeders (Post, Project) with Vietnamese sample data — all committed atomically and verified via `migrate:fresh --seed`.

## Key Decisions Made

| Decision | Rationale |
|----------|-----------|
| PHP 8.4 `#[Fillable]` attribute over `$fillable` property | Matches existing Category/Brand/Product model convention from Phase 2 |
| Synchronous email (`Mail::send()`, no queue) | Per D-16 — simpler for shared hosting; wraps in try-catch with error logging |
| Union type `Inquiry\|Contact $submission` in Mailables | Single Mailable handles both inquiry and contact types; `$type` parameter controls content branching |
| Direct `Post::create()` / `Project::create()` with hardcoded arrays | Matches existing ProductSeeder pattern; no factories needed for these seeders |

## Tasks Summary

### Task 1: Update 4 models with #[Fillable], casts, and relationships

- **Post.php**: `#[Fillable(['title', 'slug', 'excerpt', 'content', 'image', 'is_published', 'published_at'])]` — casts `is_published` as boolean, `published_at` as datetime
- **Project.php**: `#[Fillable(['title', 'slug', 'description', 'content', 'images', 'is_active'])]` — casts `images` as array (JSON), `is_active` as boolean
- **Contact.php**: `#[Fillable(['name', 'email', 'phone', 'company', 'message', 'is_read'])]` — casts `is_read` as boolean
- **Inquiry.php**: `#[Fillable(['product_id', 'name', 'email', 'phone', 'company', 'quantity', 'message', 'is_read'])]` — casts `is_read` as boolean, `quantity` as integer; `product(): BelongsTo` relationship
- **Verified**: Each model returns correct fillable array; `Inquiry::product()` returns `BelongsTo` instance

### Task 2: Create 2 Mailable classes and 2 Blade email templates

- **AdminNotification.php**: Accepts `Inquiry|Contact $submission` and `string $type`. Subject branches on type: "Yêu cầu báo giá mới từ {name}" / "Liên hệ mới từ {name}". References `mail.admin-notification` view.
- **CustomerConfirmation.php**: Same structure. Subject: "Xác nhận đã nhận yêu cầu báo giá" / "Xác nhận đã nhận liên hệ của bạn". References `mail.customer-confirmation` view.
- **admin-notification.blade.php**: HTML email with green header (matching site theme), submission details table, product info for inquiries, automatic footer.
- **customer-confirmation.blade.php**: Thank-you header, submission summary table, product info for inquiries, auto footer.
- **Verified**: Both Mailables instantiate without errors with both inquiry and contact types; email templates render correct HTML output.

### Task 3: Create PostSeeder, ProjectSeeder, update DatabaseSeeder

- **PostSeeder**: 5 published posts with Vietnamese content — company news, tech trends, PPC guide, security advice, maintenance tips.
- **ProjectSeeder**: 5 active projects with Vietnamese content — office security, shopping mall PPC, airport screening, industrial park access control, hospital equipment.
- **DatabaseSeeder**: Added `PostSeeder::class` and `ProjectSeeder::class` calls after existing seeders.
- **Storage dirs**: `storage/app/public/posts/` and `storage/app/public/projects/` created.
- **Verified**: `php artisan migrate:fresh --seed` completes with 0 errors; `Post::count()` = 5, `Project::count()` = 5.

## Deviations from Plan

None — plan executed exactly as written.

## Known Stubs

None — all seed data uses realistic placeholder paths matching the existing public disk structure (`posts/sample-*.jpg`, `projects/sample-*.jpg`). Actual images will be uploaded through the Filament admin panel.

## Threat Flags

None — no new network endpoints, auth paths, or file access patterns introduced in this plan.

## Verification

- [x] All 4 models load without errors with correct fillable arrays
- [x] `Inquiry::product()` returns `BelongsTo` relationship instance
- [x] Both Mailables instantiate with both Inquiry and Contact types; correct subjects and view references
- [x] Email templates render valid HTML output
- [x] PostSeeder creates 5 posts with all required fields
- [x] ProjectSeeder creates 5 projects with JSON image arrays
- [x] `php artisan migrate:fresh --seed` completes with 0 errors
- [x] Posts count: 5, Projects count: 5

## Commit History

| Commit | Description |
|--------|-------------|
| `9608e05` | feat(03-01): implement Post, Project, Contact, Inquiry models with #[Fillable], casts, and relationships |
| `c31e03a` | feat(03-01): create AdminNotification and CustomerConfirmation Mailables with Blade email templates |
| `5726f46` | feat(03-01): create PostSeeder, ProjectSeeder with Vietnamese sample data, update DatabaseSeeder |

## Self-Check: PASSED

- [x] `app/Models/Post.php` — exists, contains #[Fillable] and casts
- [x] `app/Models/Project.php` — exists, contains #[Fillable], `images` => 'array' cast
- [x] `app/Models/Contact.php` — exists, contains #[Fillable]
- [x] `app/Models/Inquiry.php` — exists, contains #[Fillable], BelongsTo relationship
- [x] `app/Mail/AdminNotification.php` — exists, contains envelope() and content()
- [x] `app/Mail/CustomerConfirmation.php` — exists, contains envelope() and content()
- [x] `resources/views/mail/admin-notification.blade.php` — exists, 60+ lines
- [x] `resources/views/mail/customer-confirmation.blade.php` — exists, 80+ lines
- [x] `database/seeders/PostSeeder.php` — exists, contains Post::create calls
- [x] `database/seeders/ProjectSeeder.php` — exists, contains Project::create calls
- [x] `database/seeders/DatabaseSeeder.php` — calls PostSeeder and ProjectSeeder
- [x] `php artisan migrate:fresh --seed` — completes with 0 errors

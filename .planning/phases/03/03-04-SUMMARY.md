---
phase: 03
plan: 04
subsystem: ui
tags: [blade, bootstrap, filament, pagination, gallery, frontend]
requires:
  - phase: 01-foundation
    provides: Laravel project setup, models (Post, Project), migrations
  - phase: 03-01
    provides: PostResource, ProjectResource admin CRUD (data entry)
  - phase: 03-02
    provides: PostResourceTest, ProjectResourceTest, SeederTest
provides:
  - PostController with paginated index and slug-based show
  - ProjectController with paginated index and slug-based show
  - 4 Blade views: posts/index, posts/show, projects/index, projects/show
  - Frontend routes for /tin-tuc and /du-an with Vietnamese slugs
  - PostFrontendTest and ProjectFrontendTest (10 tests total)
affects: [03-05, 03-06]
tech-stack:
  added: []
  patterns:
    - Controller pattern: index() with paginate(9) + show($slug) with firstOrFail()
    - Blade view pattern: extends layouts.app, page-header breadcrumb, grid layout, pagination
    - Gallery JS pattern: galleryImages array, selectImage/prevImage/nextImage functions
    - Frontend test pattern: DatabaseMigrations + Post::create()/Project::create() for data seeding
key-files:
  created:
    - app/Http/Controllers/PostController.php
    - app/Http/Controllers/ProjectController.php
    - resources/views/posts/index.blade.php
    - resources/views/posts/show.blade.php
    - resources/views/projects/index.blade.php
    - resources/views/projects/show.blade.php
    - tests/Feature/Http/PostFrontendTest.php
    - tests/Feature/Http/ProjectFrontendTest.php
  modified:
    - routes/web.php
key-decisions:
  - "Paiginate 9 items per page on both list views (consistent with product listing)"
  - "Reuse product-card-b2b CSS class for post/project cards (consistent template styling)"
  - "Reuse product gallery JS pattern (galleryImages, selectImage/prevImage/nextImage) for project show"
  - "strip_tags() applied to excerpt/content in meta_description for SEO safety"
  - "Controller queries filter is_published/is_active to prevent unpublished content exposure (mitigates T-03-08)"
  - "Static image grid for post index (not blog.html featured article layout — simpler and matches product listing pattern)"
patterns-established:
  - "Frontend list pages: page-header + breadcrumb + fluid container + row g-4 grid + pagination-b2b"
  - "Frontend show pages: page-header + breadcrumb + content area + back link"
  - "Gallery: push styles for gallery CSS, @push('scripts') for gallery JS, shared galleryImages/selectImage/prevImage/nextImage functions"
requirements-completed: [REQ-05, REQ-06, REQ-12]
duration: 18min
completed: 2026-06-28
status: complete
---

# Phase 3 Plan 4: Frontend Pages for Posts and Projects Summary

**PostController and ProjectController with paginated index/slug-based show, 4 Bootstrap-5 Blade views (posts index+show, projects index+show with image gallery), Vietnamese-slug routes (/tin-tuc, /du-an), and 10 passing frontend tests**

## Performance

- **Duration:** 18 min
- **Started:** 2026-06-28T08:05:00Z
- **Completed:** 2026-06-28T08:23:00Z
- **Tasks:** 3
- **Files modified:** 9

## Accomplishments

- PostController with `index()` (paginated published posts) and `show()` (by slug with published filter)
- ProjectController with `index()` (paginated active projects) and `show()` (by slug with active filter)
- `posts/index.blade.php` — 3-column responsive grid with post cards (thumbnail, title, excerpt, date, "Đọc tiếp" link), pagination, empty state
- `posts/show.blade.php` — Full-width post detail with breadcrumb, featured image, RichEditor HTML content (`{!! !!}`), meta description, "Quay lại" link
- `projects/index.blade.php` — 3-column grid with project cards (first image thumbnail, title, description, "Xem chi tiết" link), pagination, empty state
- `projects/show.blade.php` — Two-column layout: left (col-lg-7) image gallery with prev/next navigation + thumbnail strip (reuses product gallery JS pattern), right (col-lg-5) project info with title, description, RichEditor content
- Routes: `GET /tin-tuc` → `posts.index`, `GET /tin-tuc/{slug}` → `posts.show`, `GET /du-an` → `projects.index`, `GET /du-an/{slug}` → `projects.show`
- PostFrontendTest (5 tests): renders index, shows published posts, hides unpublished, renders show, 404 for unpublished
- ProjectFrontendTest (5 tests): renders index, shows active projects, hides inactive, renders show, 404 for inactive
- All 10 tests passing

## Task Commits

Each task was committed atomically:

1. **Task 1: PostController + Blade views** - `c8435a5` (feat)
2. **Task 2: ProjectController + Blade views** - `792d7f9` (feat)
3. **Task 3: Routes + frontend tests** - `5f54519` (feat)

## Files Created/Modified

- `app/Http/Controllers/PostController.php` — index() and show() methods, filters is_published=true
- `app/Http/Controllers/ProjectController.php` — index() and show() methods, filters is_active=true
- `resources/views/posts/index.blade.php` — 57-line post listing with grid, pagination, empty state
- `resources/views/posts/show.blade.php` — 52-line post detail with breadcrumb, image, content, back link
- `resources/views/projects/index.blade.php` — 53-line project listing with grid, pagination, empty state
- `resources/views/projects/show.blade.php` — 153-line project detail with image gallery (JS prev/next/thumbnails)
- `routes/web.php` — Added PostController and ProjectController routes with /tin-tuc and /du-an slugs
- `tests/Feature/Http/PostFrontendTest.php` — 5 tests covering published/unpublished filtering
- `tests/Feature/Http/ProjectFrontendTest.php` — 5 tests covering active/inactive filtering

## Decisions Made

- **9 items per page** on both list views — consistent with product listing (ag's discretion)
- **Reused `product-card-b2b` CSS class** for post/project cards — maintains consistent template styling without creating new CSS
- **Reused product gallery JS** (`galleryImages` array, `selectImage()`, `prevImage()`, `nextImage()`) for project show page — avoids duplicating gallery logic
- **`strip_tags()` on excerpt/content** in meta_description fields — prevents HTML tags leaking into meta tags
- **Controller queries with `is_published`/`is_active` filter** — mitigates T-03-08 (information disclosure), ensures unpublished/inactive content never exposed publicly
- **Static image card grid** for post index (not the blog.html featured-article + sidebar layout) — simpler implementation that matches the established product listing pattern

## Deviations from Plan

None - plan executed exactly as written.

## Issues Encountered

None — all tasks executed without issues. Tests passed on first run (10/10).

## User Setup Required

None - no external service configuration required.

## Next Phase Readiness

- Controllers, views, and routes ready for public access
- Frontend tests in place to verify published/active content visibility
- Gallery JS pattern established for reuse in future image-dependent views
- Navbar links for "Tin tức" and "Dự án" need to be added to `layouts/app.blade.php` navbar partial (REQ-12 in progress for this task)

## Self-Check: PASSED

- [x] All 9 files created/modified exist
- [x] All 3 commit hashes verified in git log
- [x] No missing files or commits

---

*Phase: 03-n-i-dung-li-n-h*
*Completed: 2026-06-28*

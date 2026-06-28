---
phase: 04
plan: 07
subsystem: Seeders & Search
tags:
  - i18n
  - seeders
  - bilingual
  - search-queries
  - json-columns
  - translatable
requires:
  - 04-01
  - 04-02
  - 04-03
  - 04-04
  - 04-05
  - 04-06
provides:
  - Bilingual seed data for all models
  - JSON path search queries for translatable columns
  - Seeder re-seed capability with bilingual content
affects:
  - database/seeders/
  - app/Http/Controllers/ProductController.php
  - app/Http/Controllers/CategoryController.php
tech-stack:
  added: []
  patterns:
    - "Translatable array format ['vi' => '...', 'en' => '...'] for all seeder translatable fields"
    - "MySQL JSON path syntax (->vi, ->en) for search and sort queries on JSON columns"
key-files:
  created: []
  modified:
    - database/seeders/CategorySeeder.php
    - database/seeders/BrandSeeder.php
    - database/seeders/ProductSeeder.php
    - database/seeders/PostSeeder.php
    - database/seeders/ProjectSeeder.php
    - app/Http/Controllers/ProductController.php
    - app/Http/Controllers/CategoryController.php
decisions: []
metrics:
  duration: "~15 min"
  completed_date: "2026-06-28"
status: complete
---

# Phase 04 Plan 07: Seeders & Search Query Migration

**One-liner:** Updated all 5 seeders to use translatable `['vi' => '...', 'en' => '...']` array format with English translations, and fixed ProductController/CategoryController search/sort queries to use MySQL JSON column path syntax.

## Tasks Executed

| # | Name | Type | Files | Commit |
|---|------|------|-------|--------|
| 1 | Update CategorySeeder and BrandSeeder with bilingual data | auto | `database/seeders/CategorySeeder.php`, `database/seeders/BrandSeeder.php` | `50cb4fc` |
| 2 | Update ProductSeeder with bilingual data | auto | `database/seeders/ProductSeeder.php` | `acb0d2c` |
| 3 | Update PostSeeder and ProjectSeeder with bilingual data | auto | `database/seeders/PostSeeder.php`, `database/seeders/ProjectSeeder.php` | `0dfe9cd` |
| 4 | Fix search queries in ProductController and CategoryController | auto | `app/Http/Controllers/ProductController.php`, `app/Http/Controllers/CategoryController.php` | `e368de5` |

## Changes Made

### Task 1: CategorySeeder + BrandSeeder

**CategorySeeder (22 categories, 3-level hierarchy):**
- All `name` fields changed from `'name' => '...'` to `'name' => ['vi' => '...', 'en' => 'English Translation']`
- All `description` fields changed to translatable array format
- Slugs kept unchanged (shared/non-translatable per D-02)
- English translations provided for all 22 category names and descriptions

**BrandSeeder (5 brands):**
- Brand `name` fields kept as proper nouns (non-translatable)
- All `description` fields changed to `['vi' => '...', 'en' => '...']` format
- Full English translations provided for each brand description

### Task 2: ProductSeeder

**13 products across 10 subcategories:**
- `name`, `short_description`, `description` → translatable `['vi' => '...', 'en' => '...']` format
- Full English translations for all product names, short descriptions, and detailed descriptions
- Non-translatable fields (slug, sku, unit, price, min_order_qty, images, brochure, technical_specs arrays, booleans) kept unchanged

### Task 3: PostSeeder + ProjectSeeder

**PostSeeder (5 posts):**
- `title`, `excerpt`, `content` → translatable array format
- Full English translations for all posts

**ProjectSeeder (5 projects):**
- `title`, `description`, `content` → translatable array format
- Full English translations for all projects

**DatabaseSeeder:** No changes needed — seed order (Category → Brand → Product → Post → Project) is already correct.

### Task 4: Controller Search Query Fixes

**ProductController:**
- `where('name', 'like', ...)` → `where('name->vi', 'like', ...)` + `orWhere('name->en', 'like', ...)`
- `orWhere('short_description', 'like', ...)` → `orWhere('short_description->vi', 'like', ...)` + `orWhere('short_description->en', 'like', ...)`
- `orderBy('name')` → `orderBy('name->vi')`
- `orderBy('name', 'desc')` → `orderBy('name->vi', 'desc')`

**CategoryController:**
- Same search query changes applied
- Same sort order changes applied

**HomeController:** No search queries — only `where('is_active', true)` and `where('is_featured', true)` which are boolean (non-translatable) fields.

**PostController / ProjectController:** No name-based search queries — no changes needed.

## Verified Truths (from must_haves)

| Truth | Status |
|-------|--------|
| All seeders use translatable array format for translatable fields | ✓ |
| English translations exist alongside Vietnamese in seeders | ✓ |
| ProductController and CategoryController search use name->vi and name->en JSON path | ✓ |
| Database can be re-seeded with bilingual content | ✓ |

## Deviations

None — plan executed exactly as written.

## Threat Flags

No new security-relevant surface introduced. Search input remains parameterized through Laravel query builder (protected from SQL injection). JSON path syntax (`->`) is parsed by MySQL's JSON_UNQUOTE(JSON_EXTRACT(...)), not by PHP — no injection vector.

## Known Stubs

None.

## Self-Check

- [x] All 4 tasks committed atomically
- [x] Files modified: CategorySeeder, BrandSeeder, ProductSeeder, PostSeeder, ProjectSeeder, ProductController, CategoryController
- [x] All translatable fields use `['vi' => '...', 'en' => '...']` format
- [x] Search queries use `name->vi` / `name->en` JSON path syntax
- [x] Sort queries use `name->vi`
- [x] English translations provided for all seeded content

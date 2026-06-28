---
phase: 04
plan: 03
subsystem: "Filament Admin — i18n tabbed UI"
tags: ["i18n", "filament", "translatable", "locale-switcher", "admin-panel"]
requires: ["04-01"]
provides: ["REQ-i18n-02"]
affects: ["app/Filament/Resources/*"]
tech-stack:
  added: ["lara-zeus/spatie-translatable v2"]
  patterns:
    - "Resource Translatable concern per Resource"
    - "Page Translatable trait per Create/Edit page"
    - "LocaleSwitcher action in header actions array"
key-files:
  created: []
  modified:
    - app/Filament/Resources/Categories/CategoryResource.php
    - app/Filament/Resources/Categories/Pages/CreateCategory.php
    - app/Filament/Resources/Categories/Pages/EditCategory.php
    - app/Filament/Resources/Brands/BrandResource.php
    - app/Filament/Resources/Brands/Pages/CreateBrand.php
    - app/Filament/Resources/Brands/Pages/EditBrand.php
    - app/Filament/Resources/Products/ProductResource.php
    - app/Filament/Resources/Products/Pages/CreateProduct.php
    - app/Filament/Resources/Products/Pages/EditProduct.php
    - app/Filament/Resources/Posts/PostResource.php
    - app/Filament/Resources/Posts/Pages/CreatePost.php
    - app/Filament/Resources/Posts/Pages/EditPost.php
    - app/Filament/Resources/Projects/ProjectResource.php
    - app/Filament/Resources/Projects/Pages/CreateProject.php
    - app/Filament/Resources/Projects/Pages/EditProject.php
decisions:
  - "Replaced abandoned filament/spatie-laravel-translatable-plugin with lara-zeus/spatie-translatable v2 (Filament v5 compatible fork)"
  - "Used LaraZeus namespaced Translatable traits instead of original Filament\Resources\Concerns\Translatable API"
metrics:
  duration: "~15 min"
  completed_date: "2026-06-28"
status: complete
---

# Phase 4 Plan 3: Filament Translatable Tabbed UI

**One-liner:** Added LaraZeus Spatie Translatable concern to all 5 Filament Resources and LocaleSwitcher actions to all 10 Create/Edit pages, enabling VI|EN tabbed locale switching in the admin panel.

## Overview

This plan implements REQ-i18n-02: the Filament tabbed VI|EN admin interface. All 5 Filament Resources (Category, Brand, Product, Post, Project) now have the `Translatable` concern with `getTranslatableLocales()` returning `['vi', 'en']`. All 5 Create pages and 5 Edit pages have the corresponding `Translatable` trait and a `LocaleSwitcher` action in their header.

The form schemas themselves remain unchanged — the plugin auto-wraps translatable fields in locale tabs based on the model's `$translatable` array.

## Deviations from Plan

### Package Substitution (Deviation — Architecture)

**Issue:** The plan specified `filament/spatie-laravel-translatable-plugin`, which is **abandoned** and only supports Filament v3. This project uses Filament v5.6.7.

**Substitution:** Installed `lara-zeus/spatie-translatable` v2.0.0, the community-maintained fork that supports Filament v5.x. This package is listed on the official Filament plugins page and has 5M+ downloads on Packagist.

**API changes due to substitution:**
| Original (plan spec) | Actual (lara-zeus) |
|---|---|
| `Filament\Resources\Concerns\Translatable` | `LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable` |
| `Filament\Resources\Pages\CreateRecord\Concerns\Translatable` | `LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable` |
| `Filament\Resources\Pages\EditRecord\Concerns\Translatable` | `LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable` |
| `\Filament\Actions\LocaleSwitcher::make()` | `LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher::make()` |

## Tasks Executed

### Task 1: Translatable concern on all 5 Resources
- **Commit:** `4a24a64`
- Added `LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable` trait to CategoryResource, BrandResource, ProductResource, PostResource, ProjectResource
- Each overrides `getTranslatableLocales()` to return `['vi', 'en']`
- Installed lara-zeus/spatie-translatable via composer

### Task 2: Translatable concern + LocaleSwitcher on all 5 Create pages
- **Commit:** `c87ead0`
- Added `LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable` + `LocaleSwitcher` action to CreateCategory, CreateBrand, CreateProduct, CreatePost, CreateProject
- CreateProduct retains its existing `afterSave()` image resize logic — trait coexistence verified

### Task 3: Translatable concern + LocaleSwitcher on all 5 Edit pages
- **Commit:** `b18bb02`
- Added `LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable` + `LocaleSwitcher::make()` to EditCategory, EditBrand, EditProduct, EditPost, EditProject
- `LocaleSwitcher::make()` added before existing `DeleteAction::make()` in all header actions
- EditProduct retains its existing `afterSave()` image resize logic

## Verification Results

All 15 classes verified via ReflectionClass at runtime:
- ✅ 5 Resources: `Translatable` trait present, `getTranslatableLocales()` returns `['vi', 'en']`
- ✅ 5 Create pages: `Translatable` trait present, `getHeaderActions()` defined
- ✅ 5 Edit pages: `Translatable` trait present, `getHeaderActions()` defined
- ✅ CreateProduct and EditProduct: existing `afterSave()` methods coexist with Translatable trait

## Threat Surface

No new threat surface introduced. The LocaleSwitcher only controls the active locale tab in the admin form — server-side validation is still handled by spatie/laravel-translatable and Filament's authorization. See threat register T-04-03-01 (accepted) and T-04-03-02 (accepted).

## Self-Check: PASSED

- SUMMARY.md written to `.planning/phases/04/04-03-SUMMARY.md`
- All 3 commits confirmed via `git log`
- All 15 files modified verified via ReflectionClass runtime checks
- No stub patterns found (changes are trait/import additions only)
- No deferred items

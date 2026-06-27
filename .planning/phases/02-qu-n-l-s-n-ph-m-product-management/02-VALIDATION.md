---
phase: 02
slug: qu-n-l-s-n-ph-m-product-management
status: draft
nyquist_compliant: false
wave_0_complete: false
created: 2026-06-27
---

# Phase 02 — Validation Strategy

> Per-phase validation contract for feedback sampling during execution.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | PHPUnit 11.x (Laravel default) |
| **Config file** | `phpunit.xml` |
| **Quick run command** | `php artisan test --parallel` |
| **Full suite command** | `php artisan test` |
| **Estimated runtime** | ~30s |

---

## Sampling Rate

- **After every task commit:** Run `php artisan test --parallel`
- **After every plan wave:** Run `php artisan test`
- **Before `/gsd-verify-work`:** Full suite must be green
- **Max feedback latency:** 60 seconds

---

## Per-Task Verification Map

| Task ID | Plan | Wave | Requirement | Threat Ref | Secure Behavior | Test Type | Automated Command | File Exists | Status |
|---------|------|------|-------------|------------|-----------------|-----------|-------------------|-------------|--------|
| 02-01-01 | 01 | 1 | REQ-01 | — | N/A | unit | `php artisan test --filter=CategoryResource` | ❌ | ○ pending |
| 02-01-02 | 01 | 1 | REQ-02 | — | N/A | unit | `php artisan test --filter=BrandResource` | ❌ | ○ pending |
| 02-02-01 | 02 | 1 | REQ-03 | — | N/A | unit | `php artisan test --filter=ProductResource` | ❌ | ○ pending |
| 02-03-01 | 03 | 2 | REQ-04 | — | N/A | feature | `php artisan test --filter=HomePageTest` | ❌ | ○ pending |
| 02-04-01 | 04 | 2 | REQ-05 | — | N/A | feature | `php artisan test --filter=CategoryPageTest` | ❌ | ○ pending |
| 02-05-01 | 05 | 3 | REQ-06 | — | N/A | feature | `php artisan test --filter=ProductDetailTest` | ❌ | ○ pending |
| 02-06-01 | 06 | 3 | REQ-07 | — | N/A | feature | `php artisan test --filter=SearchTest` | ❌ | ○ pending |

*Status: ○ pending ● green ✗ red ~ flaky*

---

## Wave 0 Requirements

- [ ] `tests/Feature/Admin/CategoryResourceTest.php` — stubs for category CRUD
- [ ] `tests/Feature/Admin/BrandResourceTest.php` — stubs for brand CRUD
- [ ] `tests/Feature/Admin/ProductResourceTest.php` — stubs for product CRUD
- [ ] `tests/Feature/Frontend/HomePageTest.php` — stubs for homepage
- [ ] `tests/Feature/Frontend/CategoryPageTest.php` — stubs for category listing
- [ ] `tests/Feature/Frontend/ProductDetailTest.php` — stubs for product detail
- [ ] `tests/Feature/Frontend/SearchTest.php` — stubs for search

*Existing infrastructure covers all phase requirements.*

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| Cascading category dropdown admin UI | REQ-01 | Filament JS-dependent live dropdown | Log into /admin, create category, verify parent dropdown cascades |
| Product image upload + resize | REQ-03 | Intervention Image processing | Upload test image in admin product form, verify 600x600 thumbnail created |
| Category tree sidebar expand/collapse | REQ-05 | Client-side JS animation | Visit category page, click tree nodes, verify expand/collapse behavior |
| Product gallery lightbox navigation | REQ-06 | Client-side JS | Click product gallery image, verify prev/next navigation works |
| Mobile responsive layout | REQ-04-07 | Visual regression | Resize browser to mobile width, verify layout adapts |

---

## Validation Sign-Off

- [ ] All tasks have `<automated>` verify or Wave 0 dependencies
- [ ] Sampling continuity: no 3 consecutive tasks without automated verify
- [ ] Wave 0 covers all MISSING references
- [ ] No watch-mode flags
- [ ] Feedback latency < 60s
- [ ] `nyquist_compliant: true` set in frontmatter

**Approval:** pending

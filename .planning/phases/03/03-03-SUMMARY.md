---
phase: 03
plan: 03
subsystem: Filament Admin
tags:
  - inquiry-resource
  - contact-resource
  - read-only
  - view-record
  - filament
depends_on:
  - "03-01"
requires: []
provides:
  - InquiryResource
  - ContactResource
affects:
  - app/Filament/Resources/Inquiries/
  - app/Filament/Resources/Contacts/
tech-stack:
  added: []
  patterns:
    - "Read-only ViewRecord pattern with DeleteAction header"
    - "Form fields disabled with ->disabled()->dehydrated(false)"
    - "View page auto-marks is_read via mutateFormDataBeforeFill"
    - "List page without CreateAction (customer-submitted data)"
key-files:
  created:
    - app/Filament/Resources/Inquiries/InquiryResource.php
    - app/Filament/Resources/Inquiries/Schemas/InquiryForm.php
    - app/Filament/Resources/Inquiries/Tables/InquiriesTable.php
    - app/Filament/Resources/Inquiries/Pages/ListInquiries.php
    - app/Filament/Resources/Inquiries/Pages/ViewInquiry.php
    - app/Filament/Resources/Contacts/ContactResource.php
    - app/Filament/Resources/Contacts/Schemas/ContactForm.php
    - app/Filament/Resources/Contacts/Tables/ContactsTable.php
    - app/Filament/Resources/Contacts/Pages/ListContacts.php
    - app/Filament/Resources/Contacts/Pages/ViewContact.php
    - tests/Feature/Filament/InquiryResourceTest.php
    - tests/Feature/Filament/ContactResourceTest.php
  modified: []
decisions:
  - "ViewRecord + DeleteAction (no EditRecord) per D-14 — admin cannot edit customer submissions"
  - "is_read auto-marks on view via mutateFormDataBeforeFill (no edit toggle needed)"
  - "Form fields use ->disabled()->dehydrated(false) so data displays read-only without exposing write paths"
  - "Livewire::test() for delete action testing since Filament uses Livewire action dispatch"
metrics:
  duration: "4 minutes"
  completed_date: "2026-06-28"
  tasks: 3
  files_created: 12
  test_count: 8
status: complete

# Phase 3 Plan 03: Inquiry & Contact Filament Resources (Read-only management)

**One-liner:** Created InquiryResource and ContactResource with read-only ViewRecord pattern, auto-mark is_read on view, DeleteAction header, and no create/edit pages — 12 files, 8 passing tests, navigationSort=6 and 7.

## Objective

Create two read-only Filament Resources for managing customer-submitted inquiries (quote requests) and contacts. Per D-14, these are view-only — admin can view details, mark as read (automatic on view), and delete, but cannot edit customer submissions.

## Task Breakdown

### Task 1: Create InquiryResource (5 files)
**Commit:** `feat(03-03): create InquiryResource with read-only ViewRecord and DeleteAction`

| File | Purpose |
|------|---------|
| `app/Filament/Resources/Inquiries/InquiryResource.php` | Resource class, navigationSort=6, icon=heroicon-o-chat-bubble-left-right, only index+view pages |
| `app/Filament/Resources/Inquiries/Schemas/InquiryForm.php` | Read-only form schema with disabled fields (name, email, phone, company, quantity, product, message, is_read, created_at) |
| `app/Filament/Resources/Inquiries/Tables/InquiriesTable.php` | Table with name, email, phone, company, product.name, is_read (IconColumn boolean), created_at; TernaryFilter for is_read; ViewAction + DeleteAction |
| `app/Filament/Resources/Inquiries/Pages/ListInquiries.php` | ListRecords page — no CreateAction header |
| `app/Filament/Resources/Inquiries/Pages/ViewInquiry.php` | ViewRecord page — DeleteAction header, mutateFormDataBeforeFill marks is_read=true on view |

### Task 2: Create ContactResource (5 files)
**Commit:** `feat(03-03): create ContactResource with read-only ViewRecord and DeleteAction`

| File | Purpose |
|------|---------|
| `app/Filament/Resources/Contacts/ContactResource.php` | Resource class, navigationSort=7, icon=heroicon-o-envelope, only index+view pages |
| `app/Filament/Resources/Contacts/Schemas/ContactForm.php` | Read-only form schema with disabled fields (name, email, phone, company, message, is_read, created_at) |
| `app/Filament/Resources/Contacts/Tables/ContactsTable.php` | Table with name, email, phone, company, is_read (IconColumn boolean), created_at; TernaryFilter; ViewAction + DeleteAction |
| `app/Filament/Resources/Contacts/Pages/ListContacts.php` | ListRecords page — no CreateAction header |
| `app/Filament/Resources/Contacts/Pages/ViewContact.php` | ViewRecord page — DeleteAction header, mutateFormDataBeforeFill marks is_read=true on view |

### Task 3: Tests (2 files)
**Commit:** `test(03-03): add InquiryResourceTest and ContactResourceTest`

| Test | InquiryResourceTest | ContactResourceTest |
|------|-------------------|-------------------|
| List | GET /admin/inquiries → 200, see name | GET /admin/contacts → 200, see name |
| View | GET /admin/inquiries/{id} → 200, see name | GET /admin/contacts/{id} → 200, see name |
| Mark as Read | GET view page → is_read becomes true | GET view page → is_read becomes true |
| Delete | Livewire::test → callAction('delete') → DB record removed | Livewire::test → callAction('delete') → DB record removed |

## Deviations from Plan

**None** — plan executed exactly as written.

## Test Results

```
InquiryResourceTest   ✓ 4 passed (9 assertions)  2.51s
ContactResourceTest   ✓ 4 passed (9 assertions)  2.44s
```

Both test suites pass. All 8 tests green.

## Key Decisions

1. **Livewire::test() for delete testing** — Filament uses Livewire action dispatch for delete. Standard HTTP DELETE doesn't route to the Livewire component. Used `Livewire::test(ViewInquiry::class, ['record' => $id])->callAction('delete')` per Filament v3 testing conventions.

2. **Disabled fields with dehydrated(false)** — All form fields use `->disabled()->dehydrated(false)` to display stored values without allowing edits. The `dehydrated(false)` ensures disabled fields don't submit values back to the server on any form save (though no save is possible since there's no EditRecord page).

3. **is_read auto-marking** — Implemented in `mutateFormDataBeforeFill()` which is called by `ViewRecord` during `fillFormWithDataAndCallHooks`. This fires on every view page load and persists via `$this->getRecord()->update(['is_read' => true])` directly to the database.

## Threat Surface Scan

No new threat surfaces introduced beyond what the plan's threat model covers:
- T-03-05 (Information Disclosure): Mitigated by Filament panel auth middleware
- T-03-06 (Tampering is_read): Accepted — intentional auto-mark behavior

## Known Stubs

None.

## Self-Check: PASSED

- [x] 12 created files all exist on disk
- [x] 3 commits exist in git log
- [x] All 8 tests pass
- [x] InquiryResource navigationSort=6
- [x] ContactResource navigationSort=7
- [x] No EditRecord or CreateRecord pages
- [x] DeleteAction in ViewRecord headers
- [x] is_read auto-marks on view
- [x] All form fields disabled/read-only

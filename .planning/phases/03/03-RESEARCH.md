# Phase 3: Nội dung & Liên hệ (Content & Contact) - Research

**Researched:** 2026-06-28
**Domain:** Laravel 11 + Filament 3 Content Management, Email, Frontend Forms
**Confidence:** HIGH

## Summary

Phase 3 extends the existing Laravel 11 + Filament 3 admin panel with 4 new Filament Resources (Post, Project, Inquiry, Contact), adding content management (blog/news, projects) and customer-facing contact features (inquiry form modal, contact form, email notifications). The established modular pattern from Phase 2 (Resource → Schema/Form class → Table class) must be replicated consistently. Email delivery uses Laravel Mail with synchronous SMTP transport (no queue, per D-16). Frontend modals use Bootstrap 5 with standard POST (not AJAX per D-06 discussion), requiring session-based flash messages and modal re-open logic on validation failure. Existing models are empty stubs with no fillable, casts, or relationships defined. The posts and projects migrations already exist with all required columns.

**Primary recommendation:** Replicate Phase 2's modular Filament pattern exactly. Use IconColumn for boolean status fields (matches existing style). Use FileUpload with `multiple()` and `reorderable()` for the project JSON gallery. Keep email synchronous. Use session flash + query parameter for modal re-open on validation error.

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions

**Posts / Tin tức**
- D-01: Posts là flat list — không có danh mục con
- D-02: Editor dùng Filament RichEditor (TinyMCE built-in) cho content — phù hợp người không chuyên
- D-03: Post có title, slug (unique), excerpt, content (RichEditor), image, is_published, published_at — migration đã có sẵn

**Projects / Dự án**
- D-04: Project chỉ có images (JSON gallery) + content — không cần equipment_list riêng
- D-05: Project có title, slug (unique), description, content, images (JSON), is_active — migration đã có sẵn

**Yêu cầu báo giá (Quote Request)**
- D-06: Modal popup ngay trên trang chi tiết sản phẩm (không redirect sang contact page)
- D-07: Modal form gồm: name, email, phone, company, quantity, message + product_id (hidden)
- D-08: Lưu vào inquiries table (đã có migration: product_id, name, email, phone, company, quantity, message)

**Liên hệ (Contact Form)**
- D-09: Form trên contact page gồm: name, email, phone, company, message (xóa subject field, thêm phone/company match contacts table)
- D-10: Giữ nguyên Google Maps embed hiện tại trên contact page
- D-11: Lưu vào contacts table (đã có migration: name, email, phone, company, message)

**Admin (Filament)**
- D-12: Tạo 4 Filament Resources: PostResource, ProjectResource, InquiryResource (quản lý yêu cầu báo giá), ContactResource (quản lý liên hệ)
- D-13: Navigation sort: Category=1, Brand=2, Product=3, Post=4, Project=5, Inquiry=6, Contact=7
- D-14: InquiryResource + ContactResource cho phép xem, đánh dấu đã đọc (is_read), xóa — không cần edit (ghi chép của khách)

**Email**
- D-15: Dùng SMTP driver — cấu hình trong .env
- D-16: Gửi email synchronous (không queue) — đơn giản, phù hợp shared hosting
- D-17: 2 email templates: admin_notification (thông báo có inquiry/contact mới) + customer_confirmation (xác nhận đã nhận yêu cầu)
- D-18: Gửi email cho cả Inquiry (yêu cầu báo giá) và Contact (liên hệ)

**Seeders**
- D-19: PostSeeder — 3-5 bài viết mẫu có title, slug, excerpt, content, image, published_at
- D-20: ProjectSeeder — 3-5 dự án mẫu có title, slug, description, content, images, is_active

**Frontend**
- D-21: Bám sát template-frontend hiện có (Bootstrap 5, xanh lá - cam)
- D-22: Blade views mới: posts/index, posts/show, projects/index, projects/show
- D-23: Cập nhật navbar links cho Tin tức và Dự án
- D-24: Pagination cho danh sách posts/projects

### the agent's Discretion
- Số items mỗi trang (pagination count)
- Seed data content chi tiết (post titles, project names, v.v.)
- Layout chi tiết và responsive breakpoints
- Modal UI design cho quote request (bám sát Bootstrap 5 theme hiện có)
- Email template design (text-based hoặc HTML đơn giản)

### Deferred Ideas (OUT OF SCOPE)
- Post categories — Nếu sau này cần phân loại tin tức, thêm PostCategory model + category_id
- Equipment list cho Projects — Có thể thêm equipment_list JSON column nếu cần
- Mail queue — Nâng cấp lên queue job nếu shared hosting hỗ trợ
- Reply from admin — Trả lời inquiry/contact trực tiếp từ Filament (chỉ gửi email)

</user_constraints>

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|------------------|
| REQ-01 | PostResource — CRUD for blog/news | Model needs fillable/casts; Filament pattern established; RichEditor configured for content |
| REQ-02 | ProjectResource — CRUD for projects | Model needs fillable/casts with JSON array cast for images; FileUpload multiple for gallery |
| REQ-03 | InquiryResource — manage quote requests | Read-only + mark is_read + delete; SelectColumn or IconColumn for is_read |
| REQ-04 | ContactResource — manage contact submissions | Same pattern as InquiryResource; is_read boolean status |
| REQ-05 | Post frontend list/detail pages | Blade views with pagination (D-22, D-24) |
| REQ-06 | Project frontend list/detail pages | Blade views with pagination; images JSON → gallery |
| REQ-07 | Contact form page | Update existing Blade form (D-09); no subject, add phone/company; store to DB |
| REQ-08 | Quote request modal on product detail | Bootstrap 5 modal with form POST (D-06); store Inquiry; product_id hidden |
| REQ-09 | Email: admin notification for inquiry/contact | Mailable class + Blade template; Mail::to(admin) |
| REQ-10 | Email: customer confirmation for inquiry/contact | Mailable class + Blade template; Mail::to(customer) |
| REQ-11 | PostSeeder + ProjectSeeder with sample data | Model fillable/casts must be defined first |
| REQ-12 | Navbar links for Tin tức & Dự án | Update app.blade.php navbar partial |

</phase_requirements>

## Architectural Responsibility Map

| Capability | Primary Tier | Secondary Tier | Rationale |
|------------|-------------|----------------|-----------|
| Post CRUD (Admin) | Admin Panel (Filament) | — | Full resource with create/edit/delete; no frontend user submission for posts |
| Project CRUD (Admin) | Admin Panel (Filament) | — | Same as Post; admin-managed content |
| Inquiry Management | Admin Panel (Filament) | — | Read-only admin view; data comes from frontend form |
| Contact Management | Admin Panel (Filament) | — | Read-only admin view; data comes from frontend form |
| Post/Project Public Display | Frontend (Blade) | Database | Laravel controllers fetch from DB, render Blade views |
| Quote Request Modal | Frontend (Blade + Bootstrap) | API / Backend | Modal in product detail page; form POST to controller |
| Contact Form | Frontend (Blade + Bootstrap) | API / Backend | Form on contact page; POST to controller |
| Email Dispatch | API / Backend | — | Laravel Mail facade sends synchronously from controller |
| Image Upload (RichEditor) | API / Backend (storage) | Admin Panel | Files stored on public disk via Filament FileUpload |
| Project JSON Gallery | API / Backend | Frontend | Images stored as JSON array of paths; Repeater in admin; gallery in Blade |

## Standard Stack

### Core
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| Laravel Framework | ^13.8 | Application framework | Already installed; Mail, validation, Blade, Eloquent built-in |
| Filament Panel | ^5.6 | Admin panel | Already installed; provides RichEditor, FileUpload, table columns |
| Intervention Image | ^4.0 | Image resize/resample | Already installed; used in Product Pages for image optimization |

### Supporting
| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| Laravel Mail (built-in) | 13.x | Email delivery | Sending admin notifications and customer confirmations |
| Bootstrap 5 (CDN) | 5.0+ | Frontend modal, layout | Already loaded in app.blade.php; for quote request modal |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| FileUpload multiple (JSON array) | Spatie Media Library | Media Library is overkill for simple image array; FileUpload matches existing pattern |
| IconColumn boolean | ToggleColumn | IconColumn is read-only (matches Product pattern); ToggleColumn allows inline edit |
| Standard POST for modal | AJAX Livewire | POST is simpler, no extra JS complexity; requires modal re-open on validation error |
| Synchronous mail | Queue (database) | Queue requires extra setup; synchronous is simpler for shared hosting (D-16) |

**Installation:**
```bash
# No new packages needed. Existing stack covers all requirements.
```

## Package Legitimacy Audit

> No new external packages required. All functionality uses built-in Laravel/Filament features already installed.

| Package | Registry | Required | Existing in composer.json | Verdict |
|---------|----------|----------|--------------------------|---------|
| filament/filament | npm/composer | Filament resources, components | ^5.6 | OK (already installed) |
| laravel/framework | composer | Mail, Eloquent, Blade, Validation | ^13.8 | OK (already installed) |
| intervention/image-laravel | composer | Image resize for uploaded images | ^4.0 | OK (already installed) |

**Packages removed due to [SLOP] verdict:** none
**Packages flagged as suspicious [SUS]:** none

## Filament Resource Patterns

### Directory Structure (matches Phase 2)

```
app/Filament/Resources/
├── Posts/
│   ├── PostResource.php
│   ├── Schemas/
│   │   └── PostForm.php
│   ├── Tables/
│   │   └── PostsTable.php
│   └── Pages/
│       ├── ListPosts.php
│       ├── CreatePost.php
│       └── EditPost.php
├── Projects/
│   ├── ProjectResource.php
│   ├── Schemas/
│   │   └── ProjectForm.php
│   ├── Tables/
│   │   └── ProjectsTable.php
│   └── Pages/
│       ├── ListProjects.php
│       ├── CreateProject.php
│       └── EditProject.php
├── Inquiries/
│   ├── InquiryResource.php
│   ├── Schemas/
│   │   └── InquiryForm.php       (View-only, no editable fields)
│   ├── Tables/
│   │   └── InquiriesTable.php
│   └── Pages/
│       ├── ListInquiries.php
│       └── ViewInquiry.php       (ViewRecord, not EditRecord per D-14)
├── Contacts/
│   ├── ContactResource.php
│   ├── Schemas/
│   │   └── ContactForm.php       (View-only)
│   ├── Tables/
│   │   └── ContactsTable.php
│   └── Pages/
│       ├── ListContacts.php
│       └── ViewContact.php       (ViewRecord)
```

### Resource Class Pattern
Each resource follows the established pattern from CategoryResource/BrandResource/ProductResource:

```php
#[CITED: app/Filament/Resources/Products/ProductResource.php]
class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?int $navigationSort = 4;  // Per D-13
    protected static ?string $navigationLabel = 'Tin tức';
    protected static ?string $modelLabel = 'Bài viết';
    protected static ?string $pluralModelLabel = 'Tin tức';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
```

### Key Pattern Decisions
- **PostResource, ProjectResource** → Full CRUD (List, Create, Edit pages) [CITED: Phase 2 pattern]
- **InquiryResource, ContactResource** → Read-only + mark is_read + delete (use `ViewRecord` page instead of `EditRecord`, and add `DeleteAction` in header actions) [VERIFIED: CONTEXT.md D-14]
- In `getPages()`, replace `EditProduct` with `ViewInquiry` for read-only resources. The `ViewRecord` page shows fields in read-only mode (Infolist) with `DeleteAction` in header.

## RichEditor Configuration

### Post Content RichEditor
The project already uses `RichEditor` in `ProductForm.php` for product descriptions. For Post content, configure image uploads explicitly:

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->label('Nội dung')
    ->required()
    ->fileAttachmentsDisk('public')          // matches existing public disk
    ->fileAttachmentsDirectory('posts')      // organized under posts/
    ->columnSpanFull()
    ->toolbarButtons([
        'bold', 'italic', 'underline', 'strike',
        'h2', 'h3',
        'bulletList', 'orderedList',
        'blockquote',
        'link',
        'image',           // enables image upload button
        'undo', 'redo',
    ]);
```

[VERIFIED: Context7 — Filament 5.x RichEditor docs]

### Key Points
- Default storage is public (no need to specify `fileAttachmentsDisk` explicitly if using default disk, but explicit is better)
- Uploaded images are stored at `storage/app/public/posts/` and accessible via `/storage/posts/...`
- `fileAttachmentsVisibility('public')` is the default — no extra config needed
- The toolbar buttons list controls which formatting options appear; default set includes everything shown
- For the Post model, the `content` column is `longText` which is sufficient for RichEditor HTML content

### Post Image (thumbnail) vs Content Images
- Post has a separate `image` column for the thumbnail/featured image — use a separate `FileUpload` component for that
- Content images are embedded within the RichEditor HTML and stored via `fileAttachmentsDirectory`

## Boolean Fields in Filament

### For is_read (InquiryResource, ContactResource tables)
Best approach: `IconColumn` with `boolean()` — matches the existing Pattern in `ProductsTable.php` [CITED: app/Filament/Resources/Products/Tables/ProductsTable.php]:

```php
use Filament\Tables\Columns\IconColumn;

IconColumn::make('is_read')
    ->boolean()
    ->label('Đã đọc')
    ->trueColor('success')
    ->falseColor('gray')
```

### For is_published (PostResource table)
Same pattern — `IconColumn::make('is_published')->boolean()`, or use `ToggleColumn` for inline editing:

```php
// Option A: IconColumn (read-only, matches existing pattern)
IconColumn::make('is_published')
    ->boolean()
    ->label('Đã xuất bản')

// Option B: ToggleColumn (inline toggle, more convenient)
ToggleColumn::make('is_published')
    ->label('Đã xuất bản')
```

**Recommendation:** Use `IconColumn` for `is_read` (read-only status, matches Product pattern), and `ToggleColumn` for `is_published`/`is_active` (admin needs to toggle these quickly).

### For is_active (ProjectResource table)
Same as `is_published` — `ToggleColumn` recommended for quick toggling.

### Badge Alternative
For `is_read` specifically, also consider a `TextColumn` with badge for a more visual status:

```php
TextColumn::make('is_read')
    ->label('Trạng thái')
    ->badge()
    ->color(fn (bool $state): string => $state ? 'success' : 'gray')
    ->formatStateUsing(fn (bool $state): string => $state ? 'Đã đọc' : 'Chưa đọc')
```

[VERIFIED: Context7 — Filament 5.x IconColumn boolean docs, TextColumn badge docs]

## Email Setup (Mailables + SMTP)

### Current State
- `config/mail.php` already has SMTP configuration; default mailer is `log` [CITED: config/mail.php]
- `.env` has `MAIL_MAILER=log`, `MAIL_HOST=127.0.0.1`, `MAIL_PORT=2525`
- For production/shared hosting, update `.env` with SMTP credentials

### Mailable Classes to Create

```
app/Mail/
├── AdminNotification.php    (D-17: thông báo admin khi có inquiry/contact mới)
└── CustomerConfirmation.php (D-17: xác nhận cho khách hàng)
```

### AdminNotification Pattern
```php
#[CITED: Context7 — Laravel 13.x Mail docs]
namespace App\Mail;

use App\Models\Inquiry;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AdminNotification extends Mailable
{
    use Queueable;

    public function __construct(
        public Inquiry|Contact $submission,
        public string $type // 'inquiry' or 'contact'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match($this->type) {
                'inquiry' => 'Yêu cầu báo giá mới từ ' . $this->submission->name,
                'contact' => 'Liên hệ mới từ ' . $this->submission->name,
            },
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin-notification',
        );
    }
}
```

### CustomerConfirmation Pattern
```php
class CustomerConfirmation extends Mailable
{
    public function __construct(
        public Inquiry|Contact $submission,
        public string $type
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match($this->type) {
                'inquiry' => 'Xác nhận đã nhận yêu cầu báo giá',
                'contact' => 'Xác nhận đã nhận liên hệ của bạn',
            },
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer-confirmation',
        );
    }
}
```

### Email Templates (Blade)

Location: `resources/views/mail/`

```
resources/views/mail/
├── admin-notification.blade.php     (D-17)
└── customer-confirmation.blade.php  (D-17)
```

**Template design:** Simple HTML with responsive inline styles (email client compatibility). The agent has discretion over exact design per D-17. Minimal corporate HTML template with:
- Company logo/name header
- Submission details (name, email, phone, company, message)
- Footer with company info

### SMTP Configuration for Shared Hosting

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com       # or smtp.gmail.com, etc.
MAIL_PORT=587                       # TLS; 465 for SSL
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls                 # or ssl for port 465
MAIL_FROM_ADDRESS=info@domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

[VERIFIED: Context7 — Laravel 13.x Mail configuration docs]

### Sending (Synchronous, D-16)
```php
use Illuminate\Support\Facades\Mail;

// In Controller after saving Inquiry:
Mail::to('admin@example.com')->send(new AdminNotification($inquiry, 'inquiry'));
Mail::to($inquiry->email)->send(new CustomerConfirmation($inquiry, 'inquiry'));
```

**No queue — `send()` blocks until email is dispatched. This is intentional for shared hosting simplicity (D-16).**

## Modal + Form Submission

### Approach: Standard POST with Session Flash

Per D-06, the quote request opens as a modal on the product detail page. Since Vue/Livewire is not used, the recommended approach is:

1. **Bootstrap 5 modal** defined directly in `resources/views/products/show.blade.php`
2. **Form POST** to a dedicated route (e.g., `POST /yeu-cau-bao-gia`)
3. **Controller validates**, stores Inquiry, sends email, redirects back with flash
4. **On validation error:** Use a query parameter or session flag to re-open the modal

### Pattern

```blade
{{-- In products/show.blade.php --}}
{{-- Quote Request Modal --}}
<div class="modal fade" id="quoteModal" tabindex="-1" 
     data-bs-backdrop="static" {{ session('quote_errors') ? 'data-bs-show="true"' : '' }}>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yêu cầu báo giá: {{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('inquiries.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-body">
                    {{-- Validation errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    
                    {{-- Flash success --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Công ty</label>
                            <input type="text" name="company" class="form-control @error('company') is-invalid @enderror" 
                                   value="{{ old('company') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                   value="{{ old('quantity') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                      rows="4" required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Re-open modal on error or after success --}}
@push('scripts')
@if(session('quote_errors') || session('quote_success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('quoteModal'));
        modal.show();
    });
</script>
@endif
@endpush
```

### Key Design Decisions
- **Standard POST** (not AJAX) — page reloads; modal re-opens via session flag
- **Session flags:** Controller sets `session()->flash('quote_errors', true)` on validation failure, and `session()->flash('quote_success', 'Yêu cầu đã được gửi!')` on success
- **Error display:** Uses Laravel's `$errors` bag with Bootstrap's `is-invalid` / `invalid-feedback` classes
- **Success display:** Flash message shown in modal; modal closes on manual dismissal
- **Product page link change:** Change the existing `<a href="{{ route('contact') }}?product=...">` to a button with `data-bs-toggle="modal" data-bs-target="#quoteModal"` [VERIFIED: CONTEXT.md specifics]

### Controller Flow
```
InquiryController@store(Request):
  1. Validate (name*, email*, phone, company, quantity, message*, product_id)
  2. Create Inquiry record
  3. Mail::to(admin)->send(new AdminNotification(...))
  4. Mail::to(customer)->send(new CustomerConfirmation(...))
  5. Redirect back without product anchor: redirect()->back()->with('quote_success', ...)
```

### Contact Form Update (per D-09)
- Remove `subject` field from current form
- Add `phone` and `company` fields
- Keep Google Maps embed (D-10)
- POST to `ContactController@store`

## JSON Gallery (Project Images)

### Database Column
The `projects.images` column is `JSON` (nullable). [CITED: database/migrations/2026_06_20_165918_create_projects_table.php]

### Filament Admin: FileUpload Multiple
The simplest approach that matches the Product images pattern is to use `FileUpload` with `multiple()` and `reorderable()`:

```php
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;

Section::make('Hình ảnh dự án')
    ->schema([
        FileUpload::make('images')
            ->multiple()           // enables multiple file selection
            ->reorderable()        // drag-to-reorder
            ->image()              // restrict to image files
            ->disk('public')
            ->directory('projects')
            ->visibility('public')
            ->maxFiles(20)         // reasonable limit for gallery
            ->columnSpanFull()
            ->label('Hình ảnh'),
    ])
```

[VERIFIED: Context7 — Filament 5.x FileUpload docs, and confirmed by existing Phase 2 ProductForm pattern using `FileUpload::make('images')->multiple()->reorderable()->image()`]

### Why FileUpload with multiple() instead of Repeater
- The `images` column stores a simple array of paths (e.g., `["projects/img1.jpg", "projects/img2.jpg"]`) — not key-value pairs
- `FileUpload->multiple()` stores exactly this format
- A `Repeater` would be necessary only if each image had metadata (caption, alt text, order)
- This matches the Product `images` column exactly

### Model Cast
```php
protected function casts(): array
{
    return [
        'images' => 'array',
    ];
}
```

### Frontend Gallery
The existing product gallery pattern in `show.blade.php` already handles a `$product->images` array — same JS gallery code can be reused for Project detail.

## Navigation Ordering

The Phase 2 resources already use `$navigationSort` static property for ordering [CITED: CategoryResource.php, BrandResource.php, ProductResource.php]:

```php
protected static ?int $navigationSort = 1; // Category
protected static ?int $navigationSort = 2; // Brand
protected static ?int $navigationSort = 3; // Product
```

Per D-13, continue this sequential ordering:

| Resource | navigationSort | navigationIcon (recommended) |
|----------|---------------|------------------------------|
| Category | 1 | `heroicon-o-rectangle-stack` |
| Brand | 2 | `heroicon-o-building-office` |
| Product | 3 | `heroicon-o-shopping-bag` |
| Post | 4 | `heroicon-o-newspaper` |
| Project | 5 | `heroicon-o-folder-open` |
| Inquiry | 6 | `heroicon-o-chat-bubble-left-right` |
| Contact | 7 | `heroicon-o-envelope` |

**No navigationGroup needed** — all resources can remain ungrouped for simplicity. If future phases add more resources, consider grouping: `$navigationGroup = 'Quản lý nội dung'` for Post/Project, `$navigationGroup = 'Liên hệ'` for Inquiry/Contact.

## Existing Model States

### Post.php
```php
#[CURRENT STATE: Empty stub — no fillable, no casts, no relationships]
class Post extends Model
{
    // Empty — needs everything
}
```

**Required additions:**
```php
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'excerpt', 'content', 'image', 'is_published', 'published_at'])]
class Post extends Model
{
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
```
[VERIFIED: database/migrations/2026_06_20_165917_create_posts_table.php — all columns match]

### Project.php
```php
#[CURRENT STATE: Empty stub — no fillable, no casts, no relationships]
class Project extends Model
{
    // Empty — needs everything
}
```

**Required additions:**
```php
#[Fillable(['title', 'slug', 'description', 'content', 'images', 'is_active'])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'images' => 'array',     // JSON column → PHP array
            'is_active' => 'boolean',
        ];
    }
}
```
[VERIFIED: database/migrations/2026_06_20_165918_create_projects_table.php — all columns match]

### Contact.php
```php
#[CURRENT STATE: Empty stub — no fillable, no casts, no relationships]
class Contact extends Model
{
    // Empty — needs everything
}
```

**Required additions:**
```php
#[Fillable(['name', 'email', 'phone', 'company', 'message', 'is_read'])]
class Contact extends Model
{
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }
}
```
[VERIFIED: database/migrations/2026_06_20_165918_create_contacts_table.php — all columns match]

### Inquiry.php
```php
#[CURRENT STATE: Empty stub — no fillable, no casts, no relationships]
class Inquiry extends Model
{
    // Empty — needs everything
}
```

**Required additions:**
```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'name', 'email', 'phone', 'company', 'quantity', 'message', 'is_read'])]
class Inquiry extends Model
{
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'quantity' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
```
[VERIFIED: database/migrations/2026_06_20_165919_create_inquiries_table.php — all columns match; product_id has foreign key constraint]

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Rich text editor | Custom WYSIWYG | Filament RichEditor (TinyMCE) | Built-in, handles image uploads, security, toolbar customization |
| Email sending infrastructure | Custom socket-based mailer | Laravel Mail (SwiftMailer/Symfony) | Handles SMTP/TLS, attachments, MIME composition |
| Admin CRUD interface | Custom admin HTML/JS | Filament Resource | Complete CRUD with filtering, sorting, pagination, authorization |
| Image upload/resize | Custom upload handler | Filament FileUpload + Intervention | Server-side validation, storage abstraction, resize pipeline already built |
| JSON array form field | Custom JS dynamic form | Filament FileUpload (multiple) | Handles add/remove/reorder, validation, storage — stores as JSON array automatically |

**Key insight:** Every feature in Phase 3 maps directly to a built-in Filament or Laravel component. There is no need for custom build solutions — the stack already provides RichEditor for content, FileUpload for images, Mail facade for email, and Bootstrap 5 for modals.

## Runtime State Inventory

> Not applicable — Phase 3 is a greenfield feature addition (new Resources, new frontend views). No existing runtime state to migrate or rename.

## Common Pitfalls

### Pitfall 1: Modal closes on validation error
**What goes wrong:** User fills out quote modal form, submits, page reloads, modal is gone — validation errors are hidden behind the page.
**Why it happens:** Standard POST reloads the page; Bootstrap modal is not automatically re-shown.
**How to avoid:** Always add a session flash + JS check to re-open the modal when validation fails. Use `session('quote_errors')` flag in Blade to conditionally show the modal.
**Warning signs:** Users can't see validation errors; they think the form submitted but got no confirmation.

### Pitfall 2: Filament navigationSort conflict
**What goes wrong:** New resources are not assigned `$navigationSort` and appear at the bottom in arbitrary order.
**Why it happens:** Resources without `navigationSort` are sorted alphabetically after those with it.
**How to avoid:** Always set `$navigationSort` per D-13 (Category=1, Brand=2, Product=3, Post=4, Project=5, Inquiry=6, Contact=7).
**Warning signs:** Resources appear out of the specified order.

### Pitfall 3: JSON images cast not set
**What goes wrong:** Filament `FileUpload->multiple()` stores paths as JSON string in DB, but Eloquent reads it as a string instead of an array. Frontend code iterating over `$project->images` crashes.
**Why it happens:** The `images` JSON column must be cast to `array` in the model.
**How to avoid:** Always add `'images' => 'array'` in the model's `casts()` method.
**Warning signs:** `foreach ($project->images as $image)` throws "Invalid argument supplied for foreach".

### Pitfall 4: RichEditor file attachment directory clashes
**What goes wrong:** RichEditor image uploads go to the same folder as product images, causing clutter and potential naming conflicts.
**Why it happens:** Default `fileAttachmentsDirectory` is the root of the disk.
**How to avoid:** Always set `->fileAttachmentsDirectory('posts')` on the RichEditor for Post content.
**Warning signs:** Discovery of unrelated images in the `products/` directory.

### Pitfall 5: Synchronous mail blocks response
**What goes wrong:** During development with `MAIL_MAILER=log`, this is fast. On production with SMTP, `Mail::send()` blocks until the remote server responds (1-5 seconds).
**Why it happens:** D-16 explicitly chooses synchronous (no queue). This is acceptable for shared hosting.
**How to avoid:** Wrap in a try-catch to prevent email failure from breaking the user's form submission. Log the error and continue.
**Warning signs:** Form submission takes >3 seconds.

### Pitfall 6: Contact form subject field removal breaks existing route
**What goes wrong:** The current contact form has a `subject` field (line 72 in contact.blade.php). Per D-09, remove it and add phone/company.
**Why it happens:** The form is being restructured. If the route handler still expects `subject`, it will silently drop data.
**How to avoid:** The route currently uses `Route::view('lien-he', 'contact')` — no controller. Need to change to a controller route that handles the POST and validates the new fields.
**Warning signs:** `undefined array key "subject"` error.

## Code Examples

### Post: Thumbnail Image Upload + RichEditor Content
```php
// app/Filament/Resources/Posts/Schemas/PostForm.php
Section::make('Nội dung bài viết')
    ->schema([
        TextInput::make('title')
            ->required()
            ->live(true)
            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                if ($operation === 'create') {
                    $set('slug', Str::slug($state));
                }
            }),
        TextInput::make('slug')
            ->required()
            ->unique(ignoreRecord: true),
        Textarea::make('excerpt')
            ->label('Mô tả ngắn')
            ->columnSpanFull(),
        RichEditor::make('content')
            ->label('Nội dung')
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('posts')
            ->columnSpanFull(),
        FileUpload::make('image')
            ->image()
            ->disk('public')
            ->directory('posts/thumbnails')
            ->visibility('public')
            ->label('Ảnh đại diện'),
        Toggle::make('is_published')
            ->label('Đã xuất bản'),
        DateTimePicker::make('published_at')
            ->label('Ngày xuất bản'),
    ])
```

### Project: FileUpload Multiple for JSON Gallery
```php
// app/Filament/Resources/Projects/Schemas/ProjectForm.php
Section::make('Nội dung dự án')
    ->schema([
        TextInput::make('title')
            ->required()
            ->live(true)
            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                if ($operation === 'create') {
                    $set('slug', Str::slug($state));
                }
            }),
        TextInput::make('slug')
            ->required()
            ->unique(ignoreRecord: true),
        Textarea::make('description')
            ->label('Mô tả ngắn')
            ->columnSpanFull(),
        RichEditor::make('content')
            ->label('Nội dung')
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('projects/content')
            ->columnSpanFull(),
        FileUpload::make('images')
            ->multiple()
            ->reorderable()
            ->image()
            ->disk('public')
            ->directory('projects')
            ->visibility('public')
            ->maxFiles(20)
            ->columnSpanFull()
            ->label('Hình ảnh dự án'),
        Toggle::make('is_active')
            ->label('Kích hoạt')
            ->default(true),
    ])
```

### Inquiry Resource: Read-Only View
```php
// app/Filament/Resources/Inquiries/InquiryResource.php
public static function getPages(): array
{
    return [
        'index' => ListInquiries::route('/'),
        'view' => ViewInquiry::route('/{record}'),
    ];
}

// app/Filament/Resources/Inquiries/Pages/ViewInquiry.php
class ViewInquiry extends ViewRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mark as read when admin views it
        $this->getRecord()->update(['is_read' => true]);
        return $data;
    }
}
```

### Admin Notification Email Template
```blade
{{-- resources/views/mail/admin-notification.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>{{ $type === 'inquiry' ? 'Yêu cầu báo giá mới' : 'Liên hệ mới' }}</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <tr><td style="padding: 8px; font-weight: bold;">Họ tên:</td><td>{{ $submission->name }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Email:</td><td>{{ $submission->email }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Điện thoại:</td><td>{{ $submission->phone ?? '—' }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Công ty:</td><td>{{ $submission->company ?? '—' }}</td></tr>
        @if($type === 'inquiry')
        <tr><td style="padding: 8px; font-weight: bold;">Sản phẩm:</td><td>{{ $submission->product->name ?? '—' }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Số lượng:</td><td>{{ $submission->quantity ?? '—' }}</td></tr>
        @endif
        <tr><td style="padding: 8px; font-weight: bold;">Nội dung:</td><td>{{ $submission->message }}</td></tr>
    </table>
    <p style="margin-top: 20px; color: #666; font-size: 12px;">
        Email này được gửi tự động từ hệ thống quản lý website.
    </p>
</body>
</html>
```

### InquiryController (Quote Request Submission)
```php
// app/Http/Controllers/InquiryController.php
namespace App\Http\Controllers;

use App\Mail\AdminNotification;
use App\Mail\CustomerConfirmation;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'message' => 'required|string',
        ]);

        $inquiry = Inquiry::create($validated);

        // Send emails synchronously (D-16)
        try {
            Mail::to(config('mail.from.address'))
                ->send(new AdminNotification($inquiry, 'inquiry'));
            Mail::to($inquiry->email)
                ->send(new CustomerConfirmation($inquiry, 'inquiry'));
        } catch (\Exception $e) {
            Log::error('Failed to send inquiry email: ' . $e->getMessage());
        }

        return redirect()->back()->with('quote_success', 'Yêu cầu báo giá của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.');
    }
}
```

### ContactController (Contact Form Submission)
```php
// app/Http/Controllers/ContactController.php
namespace App\Http\Controllers;

use App\Mail\AdminNotification;
use App\Mail\CustomerConfirmation;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        try {
            Mail::to(config('mail.from.address'))
                ->send(new AdminNotification($contact, 'contact'));
            Mail::to($contact->email)
                ->send(new CustomerConfirmation($contact, 'contact'));
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return redirect()->route('contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    }
}
```

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| Phase 2 inline `$_GET` navigation sort | `$navigationSort` static property | Phase 2 established | Consistent numeric ordering for sidebar items |
| Separate `subject` field on contact form | Dropped `subject`, added `phone` + `company` | Phase 3 (D-09) | Contact form matches `contacts` DB schema |
| Quote button → redirect to contact page | Quote button → modal on product page | Phase 3 (D-06) | Better UX — user stays on product page |
| `Route::view` for contact page | Controller route with POST handler | Phase 3 | Required for form validation and DB storage |

**Deprecated/outdated:**
- `Route::view('lien-he', 'contact')` with query param `?product=X` — will be replaced by controller-based route that handles both GET (show) and POST (store). The product context will be passed via modal data attributes instead.

## Assumptions Log

| # | Claim | Section | Risk if Wrong |
|---|-------|---------|---------------|
| A1 | All 4 new models (Post, Project, Contact, Inquiry) have no existing fillable/casts/relationships | Existing Model States | **Low risk** — verified by reading the actual files; they are empty stubs |
| A2 | The public storage disk is already linked (`php artisan storage:link` was run in Phase 1) | RichEditor Configuration | **Medium risk** — if not linked, `/storage/` URLs will 404; include `php artisan storage:link` in setup task |
| A3 | The QuoteRequest modal uses standard POST without AJAX per D-06 | Modal + Form Submission | **Low risk** — D-06 states "modal popup" with no mention of AJAX; standard POST + session flash is simpler |

**If this table is empty:** All claims in this research were verified or cited — no user confirmation needed.

## Open Questions

1. **Quote modal: standard POST vs AJAX?**
   - What we know: D-06 says modal popup. D-07 specifies form fields. No mention of AJAX.
   - What's unclear: Whether the agent should implement AJAX submission for better UX (no page reload, modal stays visible).
   - Recommendation: Start with standard POST + session flash (simpler). If UX feedback indicates the reload is jarring, upgrade to AJAX in a future phase. The POST approach is the pragmatic default for shared hosting.

2. **Image resize for Post/Project uploads?**
   - What we know: Intervention Image is installed and used in Product pages for 600x600 resize.
   - What's unclear: Whether Post thumbnail and Project gallery images also need automatic resize.
   - Recommendation: Add `afterSave()` hook in CreatePost/EditPost and CreateProject/EditProject pages to resize uploaded images, matching the Product pattern. This is a LOW effort addition — just copy the pattern from `CreateProduct.php`.

## Verification Protocol

Before emitting the `## Package Legitimacy Audit` section, the Package Legitimacy Gate protocol was run:

- **Ecosystem:** npm/composer
- **Step 1 — Legitimacy check:** No new packages to verify; all components are built-in Laravel/Filament
- **Step 2 — Registry verification:** `filament/filament ^5.6` and `laravel/framework ^13.8` confirmed in composer.json
- **Step 3 — Postinstall script check:** Not applicable (no new packages)

## Validation Architecture

> `workflow.nyquist_validation` is `true` in `.planning/config.json` — this section is included.

### Test Framework
| Property | Value |
|----------|-------|
| Framework | PHPUnit ^12.5.12 + Orchestra Testbench (Laravel built-in) |
| Config file | `phpunit.xml` |
| Quick run command | `php artisan test --filter=Phase3` |
| Full suite command | `php artisan test` |

### Phase Requirements → Test Map
| Req ID | Behavior | Test Type | Automated Command | File Exists? |
|--------|----------|-----------|-------------------|-------------|
| REQ-01 | PostResource CRUD | filament | `php artisan test --filter=PostResourceTest` | ❌ Wave 0 |
| REQ-02 | ProjectResource CRUD | filament | `php artisan test --filter=ProjectResourceTest` | ❌ Wave 0 |
| REQ-03 | InquiryResource list/view/delete | filament | `php artisan test --filter=InquiryResourceTest` | ❌ Wave 0 |
| REQ-04 | ContactResource list/view/delete | filament | `php artisan test --filter=ContactResourceTest` | ❌ Wave 0 |
| REQ-05 | Post frontend pages render | http | `php artisan test --filter=PostFrontendTest` | ❌ Wave 0 |
| REQ-06 | Project frontend pages render | http | `php artisan test --filter=ProjectFrontendTest` | ❌ Wave 0 |
| REQ-07 | Contact form submit + validation | http | `php artisan test --filter=ContactFormTest` | ❌ Wave 0 |
| REQ-08 | Quote request form submit | http | `php artisan test --filter=InquiryFormTest` | ❌ Wave 0 |
| REQ-09 | Email: admin notification | feature | `php artisan test --filter=MailTest` | ❌ Wave 0 |
| REQ-10 | Email: customer confirmation | feature | `php artisan test --filter=MailTest` | ❌ Wave 0 |
| REQ-11 | Seeders run successfully | integration | `php artisan test --filter=SeederTest` | ❌ Wave 0 |

### Sampling Rate
- **Per task commit:** `php artisan test --filter=Phase3 --stop-on-failure`
- **Per wave merge:** `php artisan test`
- **Phase gate:** Full suite green before `/gsd-verify-work`

### Wave 0 Gaps
- [ ] `tests/Feature/Filament/PostResourceTest.php` — covers REQ-01
- [ ] `tests/Feature/Filament/ProjectResourceTest.php` — covers REQ-02
- [ ] `tests/Feature/Filament/InquiryResourceTest.php` — covers REQ-03
- [ ] `tests/Feature/Filament/ContactResourceTest.php` — covers REQ-04
- [ ] `tests/Feature/Http/PostFrontendTest.php` — covers REQ-05
- [ ] `tests/Feature/Http/ProjectFrontendTest.php` — covers REQ-06
- [ ] `tests/Feature/Http/ContactFormTest.php` — covers REQ-07
- [ ] `tests/Feature/Http/InquiryFormTest.php` — covers REQ-08
- [ ] `tests/Feature/MailTest.php` — covers REQ-09, REQ-10
- [ ] `tests/Feature/SeederTest.php` — covers REQ-11
- [ ] `tests/Feature/Http/NavbarTest.php` — covers REQ-12

## Security Domain

> `security_enforcement` is `true` in config. Required section.

### Applicable ASVS Categories

| ASVS Category | Applies | Standard Control |
|---------------|---------|-----------------|
| V2 Authentication | no | Admin routes protected by Filament auth; frontend forms are public |
| V3 Session Management | no | No custom sessions; CSRF token on all forms via `@csrf` |
| V4 Access Control | no | No role-based access; admin panel uses Filament gates |
| V5 Input Validation | yes | Laravel `$request->validate()` with specific rules per field |
| V6 Cryptography | no | No passwords or sensitive data stored |

### Known Threat Patterns for Laravel + Bootstrap

| Pattern | STRIDE | Standard Mitigation |
|---------|--------|---------------------|
| SQL injection (contact form) | Tampering | Eloquent parameterized queries — safe by default |
| XSS (RichEditor content) | Spoofing | Output with `{!! $post->content !!}` — safe because HTML comes from trusted admin; escape user input with `{{ }}` |
| CSRF (modal form) | Spoofing | `@csrf` on every form + `VerifyCsrfToken` middleware (Laravel default) |
| Email header injection | Tampering | Laravel Mail facade uses Symfony Mailer which sanitizes headers |

## Sources

### Primary (HIGH confidence)
- [VERIFIED: database/ migrations] — All 4 table schemas read and confirmed
- [VERIFIED: app/Models/] — All 4 model files read; confirmed empty stubs
- [VERIFIED: app/Filament/Resources/] — Phase 2 modular pattern read and documented
- [VERIFIED: Context7 — Filament 5.x docs] — RichEditor, IconColumn, FileUpload, navigationSort documented
- [VERIFIED: Context7 — Laravel 13.x docs] — Mail configuration, Mailable classes, validation

### Secondary (MEDIUM confidence)
- [CITED: Bootstrap 5 docs] — Modal component, form validation classes
- [CITED: Stack Overflow / Laracasts] — Modal + validation patterns for standard POST forms

### Tertiary (LOW confidence)
- No tertiary claims — all factual claims verified against codebase or official docs

## Metadata

**Confidence breakdown:**
- Standard stack: **HIGH** — All tools already installed; no new packages needed
- Architecture: **HIGH** — Pattern confirmed in Phase 2 code; CONTEXT.md decisions fully captured
- Pitfalls: **HIGH** — Based on common issues with Laravel JSON casts, Filament navigation, and Bootstrap modal handling

**Research date:** 2026-06-28
**Valid until:** 2026-07-28 (stable stack — Laravel/Filament 5.x)

<# 
.SYNOPSIS
    Verify Phase 3 (Content & Contact) deliverables
.DESCRIPTION
    Runs 60+ checks against models, seeders, Filament resources, Blade views,
    controllers, routes, mailables, and automated tests. Exits 0 if all pass, 1 if any fail.
#>

$ErrorActionPreference = 'Stop'
$ScriptDir  = Split-Path -Parent $PSCommandPath
$ProjectRoot = Split-Path -Parent $ScriptDir
Set-Location -LiteralPath $ProjectRoot

$PHP      = "C:\Users\daotu\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
$PHPUNIT  = "$ProjectRoot\vendor\bin\phpunit.bat"
$MYSQL    = "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysql.exe"
$BASE_URL = "http://127.0.0.1:8080"

$passed = 0
$failed = 0

function Test-Check {
    param([string]$Name, [scriptblock]$Script)
    try {
        & $Script | Out-Null
        Write-Host "  [PASS] $Name" -ForegroundColor Green
        $script:passed++
    } catch {
        Write-Host "  [FAIL] $Name" -ForegroundColor Red
        $msg = $_.Exception.Message.Trim()
        if ($msg) { Write-Host "         $msg" -ForegroundColor Gray }
        $script:failed++
    }
}

Write-Host "`n=========================================" -ForegroundColor Cyan
Write-Host "  Phase 3 - Content & Contact Verification" -ForegroundColor Cyan
Write-Host "=========================================`n" -ForegroundColor Cyan

# ---- 1. Models ----
Write-Host "[1/7] Models" -ForegroundColor Cyan

$modelPaths = @(
    "app\Models\Post.php",
    "app\Models\Project.php",
    "app\Models\Contact.php",
    "app\Models\Inquiry.php"
)
foreach ($m in $modelPaths) {
    $fullPath = "$ProjectRoot\$m"
    Test-Check -Name "Model exists: $m" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $m" }
    }
}

Test-Check -Name "Post has Fillable + casts" -Script {
    $content = Get-Content "$ProjectRoot\app\Models\Post.php" -Raw
    if ($content -notmatch '#\[Fillable\(') { throw "Missing #[Fillable] attribute" }
    if ($content -notmatch "'title'") { throw "Missing 'title' in Fillable" }
    if ($content -notmatch "'slug'") { throw "Missing 'slug' in Fillable" }
    if ($content -notmatch "'image'") { throw "Missing 'image' in Fillable" }
    if ($content -notmatch "'is_published'") { throw "Missing 'is_published' in Fillable" }
    if ($content -notmatch "'is_published' => 'boolean'") { throw "Missing is_published boolean cast" }
}

Test-Check -Name "Project has Fillable + array cast" -Script {
    $content = Get-Content "$ProjectRoot\app\Models\Project.php" -Raw
    if ($content -notmatch '#\[Fillable\(') { throw "Missing #[Fillable] attribute" }
    if ($content -notmatch "'title'") { throw "Missing 'title'" }
    if ($content -notmatch "'description'") { throw "Missing 'description'" }
    if ($content -notmatch "'images'") { throw "Missing 'images'" }
    if ($content -notmatch "'images' => 'array'") { throw "Missing images array cast" }
    if ($content -notmatch "'is_active' => 'boolean'") { throw "Missing is_active boolean cast" }
}

Test-Check -Name "Contact has Fillable + boolean cast" -Script {
    $content = Get-Content "$ProjectRoot\app\Models\Contact.php" -Raw
    if ($content -notmatch '#\[Fillable\(') { throw "Missing #[Fillable] attribute" }
    if ($content -notmatch "'name'") { throw "Missing 'name'" }
    if ($content -notmatch "'email'") { throw "Missing 'email'" }
    if ($content -notmatch "'phone'") { throw "Missing 'phone'" }
    if ($content -notmatch "'company'") { throw "Missing 'company'" }
    if ($content -notmatch "'message'") { throw "Missing 'message'" }
    if ($content -notmatch "'is_read' => 'boolean'") { throw "Missing is_read boolean cast" }
}

Test-Check -Name "Inquiry has Fillable + boolean cast + product relationship" -Script {
    $content = Get-Content "$ProjectRoot\app\Models\Inquiry.php" -Raw
    if ($content -notmatch '#\[Fillable\(') { throw "Missing #[Fillable] attribute" }
    if ($content -notmatch "'product_id'") { throw "Missing 'product_id'" }
    if ($content -notmatch "'name'") { throw "Missing 'name'" }
    if ($content -notmatch "'email'") { throw "Missing 'email'" }
    if ($content -notmatch "'phone'") { throw "Missing 'phone'" }
    if ($content -notmatch "'company'") { throw "Missing 'company'" }
    if ($content -notmatch "'quantity'") { throw "Missing 'quantity'" }
    if ($content -notmatch "'message'") { throw "Missing 'message'" }
    if ($content -notmatch "'is_read' => 'boolean'") { throw "Missing is_read boolean cast" }
    if ($content -notmatch "function product\(\)") { throw "Missing product() relationship" }
}

# ---- 2. Seeders ----
Write-Host "[2/7] Seeders" -ForegroundColor Cyan

$seederPaths = @(
    "database\seeders\PostSeeder.php",
    "database\seeders\ProjectSeeder.php"
)
foreach ($s in $seederPaths) {
    $fullPath = "$ProjectRoot\$s"
    Test-Check -Name "Seeder exists: $s" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $s" }
    }
}

Test-Check -Name "DatabaseSeeder chains PostSeeder + ProjectSeeder" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\DatabaseSeeder.php" -Raw
    if ($content -notmatch 'PostSeeder::class') { throw "Missing PostSeeder call" }
    if ($content -notmatch 'ProjectSeeder::class') { throw "Missing ProjectSeeder call" }
}

Test-Check -Name "PostSeeder has 5 posts" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\PostSeeder.php" -Raw
    # Count array entries by "'title'" occurrences (each post has one title key)
    $count = [regex]::Matches($content, "'title'").Count
    if ($count -lt 5) { throw "Only $count posts (expected 5+)" }
}

Test-Check -Name "ProjectSeeder has 5 projects" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\ProjectSeeder.php" -Raw
    $count = [regex]::Matches($content, "'title'").Count
    if ($count -lt 5) { throw "Only $count projects (expected 5+)" }
}

# ---- 3. Filament Resources ----
Write-Host "[3/7] Filament Resources" -ForegroundColor Cyan

$resourcePaths = @(
    "app\Filament\Resources\Posts\PostResource.php",
    "app\Filament\Resources\Posts\Schemas\PostForm.php",
    "app\Filament\Resources\Posts\Tables\PostsTable.php",
    "app\Filament\Resources\Projects\ProjectResource.php",
    "app\Filament\Resources\Projects\Schemas\ProjectForm.php",
    "app\Filament\Resources\Projects\Tables\ProjectsTable.php",
    "app\Filament\Resources\Inquiries\InquiryResource.php",
    "app\Filament\Resources\Inquiries\Pages\ViewInquiry.php",
    "app\Filament\Resources\Contacts\ContactResource.php",
    "app\Filament\Resources\Contacts\Pages\ViewContact.php"
)
foreach ($r in $resourcePaths) {
    $fullPath = "$ProjectRoot\$r"
    Test-Check -Name "Resource exists: $r" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $r" }
    }
}

Test-Check -Name "PostResource has RichEditor + ToggleColumn + navigationSort=4" -Script {
    $res = Get-Content "$ProjectRoot\app\Filament\Resources\Posts\PostResource.php" -Raw
    $form = Get-Content "$ProjectRoot\app\Filament\Resources\Posts\Schemas\PostForm.php" -Raw
    $table = Get-Content "$ProjectRoot\app\Filament\Resources\Posts\Tables\PostsTable.php" -Raw
    if ($res -notmatch "navigationSort.*= 4") { throw "navigationSort != 4" }
    if ($form -notmatch "RichEditor") { throw "Missing RichEditor" }
    if ($table -notmatch "ToggleColumn|is_published") { throw "Missing ToggleColumn for is_published" }
}

Test-Check -Name "ProjectResource has FileUpload multiple + ToggleColumn + navigationSort=5" -Script {
    $res = Get-Content "$ProjectRoot\app\Filament\Resources\Projects\ProjectResource.php" -Raw
    $form = Get-Content "$ProjectRoot\app\Filament\Resources\Projects\Schemas\ProjectForm.php" -Raw
    if ($res -notmatch "navigationSort.*= 5") { throw "navigationSort != 5" }
    if ($form -notmatch "FileUpload") { throw "Missing FileUpload" }
    if ($form -notmatch "multiple\(\)") { throw "Missing multiple()" }
}

Test-Check -Name "InquiryResource read-only pages + navigationSort=6" -Script {
    $res = Get-Content "$ProjectRoot\app\Filament\Resources\Inquiries\InquiryResource.php" -Raw
    if ($res -notmatch "navigationSort.*= 6") { throw "navigationSort != 6" }
}

Test-Check -Name "ContactResource read-only pages + navigationSort=7" -Script {
    $res = Get-Content "$ProjectRoot\app\Filament\Resources\Contacts\ContactResource.php" -Raw
    if ($res -notmatch "navigationSort.*= 7") { throw "navigationSort != 7" }
}

Test-Check -Name "Navigation sort order (Post=4, Project=5, Inquiry=6, Contact=7)" -Script {
    $a = Get-Content "$ProjectRoot\app\Filament\Resources\Posts\PostResource.php" -Raw
    $b = Get-Content "$ProjectRoot\app\Filament\Resources\Projects\ProjectResource.php" -Raw
    $c = Get-Content "$ProjectRoot\app\Filament\Resources\Inquiries\InquiryResource.php" -Raw
    $d = Get-Content "$ProjectRoot\app\Filament\Resources\Contacts\ContactResource.php" -Raw
    if ($a -notmatch "navigationSort.*= 4") { throw "Post sort != 4" }
    if ($b -notmatch "navigationSort.*= 5") { throw "Project sort != 5" }
    if ($c -notmatch "navigationSort.*= 6") { throw "Inquiry sort != 6" }
    if ($d -notmatch "navigationSort.*= 7") { throw "Contact sort != 7" }
}

# ---- 4. Blade Views & Partials ----
Write-Host "[4/7] Blade Views & Partials" -ForegroundColor Cyan

$viewPaths = @(
    "resources\views\posts\index.blade.php",
    "resources\views\posts\show.blade.php",
    "resources\views\projects\index.blade.php",
    "resources\views\projects\show.blade.php",
    "resources\views\contact.blade.php",
    "resources\views\partials\navbar.blade.php"
)
foreach ($v in $viewPaths) {
    $fullPath = "$ProjectRoot\$v"
    Test-Check -Name "View exists: $v" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $v" }
    }
}

Test-Check -Name "Post index has excerpt + image + pagination" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\posts\index.blade.php" -Raw
    if ($content -notmatch "excerpt|Str::limit") { throw "Missing excerpt rendering" }
    if ($content -notmatch "asset\('storage/") { throw "Missing storage asset() path" }
    if ($content -notmatch "links\(\)") { throw "Missing pagination links" }
}

Test-Check -Name "Post show renders HTML content" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\posts\show.blade.php" -Raw
    if ($content -notmatch "content") { throw "Missing content rendering" }
    if ($content -notmatch '\{\!\![^!]*content[^!]*\!\!\}') { throw "Missing raw HTML output for RichEditor" }
}

Test-Check -Name "Project index has card grid with description" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\projects\index.blade.php" -Raw
    if ($content -notmatch "description|Str::limit") { throw "Missing description rendering" }
    if ($content -notmatch "links\(\)") { throw "Missing pagination links" }
    if ($content -notmatch "project-card|product-card") { throw "Missing card class" }
}

Test-Check -Name "Project show has image gallery" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\projects\show.blade.php" -Raw
    if ($content -notmatch "images|foreach") { throw "Missing images loop" }
}

Test-Check -Name "Contact form has phone + company fields + Google Maps" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\contact.blade.php" -Raw
    if ($content -notmatch "name") { throw "Missing name field" }
    if ($content -notmatch "email") { throw "Missing email field" }
    if ($content -notmatch "phone") { throw "Missing phone field" }
    if ($content -notmatch "company") { throw "Missing company field" }
    if ($content -notmatch "message") { throw "Missing message field" }
    if ($content -notmatch "google\\.com/maps|maps/embed") { throw "Missing Google Maps embed" }
}

Test-Check -Name "Navbar has Tin tuc + Du an links with active detection" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\partials\navbar.blade.php" -Raw
    if ($content -notmatch "route\('posts.index'\)") { throw "Missing Tin tuc link (route)" }
    if ($content -notmatch "route\('projects.index'\)") { throw "Missing Du an link (route)" }
    if ($content -notmatch "routeIs\('posts\.\*'\)") { throw "Missing active detection for posts" }
    if ($content -notmatch "routeIs\('projects\.\*'\)") { throw "Missing active detection for projects" }
}

# ---- 5. Controllers & Routes ----
Write-Host "[5/7] Controllers & Routes" -ForegroundColor Cyan

$controllerPaths = @(
    "app\Http\Controllers\PostController.php",
    "app\Http\Controllers\ProjectController.php",
    "app\Http\Controllers\ContactController.php",
    "app\Http\Controllers\InquiryController.php"
)
foreach ($c in $controllerPaths) {
    $fullPath = "$ProjectRoot\$c"
    Test-Check -Name "Controller exists: $c" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $c" }
    }
}

Test-Check -Name "PostController has index + show by slug" -Script {
    $content = Get-Content "$ProjectRoot\app\Http\Controllers\PostController.php" -Raw
    if ($content -notmatch "function index") { throw "Missing index()" }
    if ($content -notmatch "function show") { throw "Missing show()" }
    if ($content -notmatch "paginate") { throw "Missing paginate" }
    if ($content -notmatch "slug") { throw "Missing slug lookup" }
}

Test-Check -Name "ProjectController has index + show by slug" -Script {
    $content = Get-Content "$ProjectRoot\app\Http\Controllers\ProjectController.php" -Raw
    if ($content -notmatch "function index") { throw "Missing index()" }
    if ($content -notmatch "function show") { throw "Missing show()" }
    if ($content -notmatch "paginate") { throw "Missing paginate" }
    if ($content -notmatch "slug") { throw "Missing slug lookup" }
}

Test-Check -Name "ContactController has show + store with validation" -Script {
    $content = Get-Content "$ProjectRoot\app\Http\Controllers\ContactController.php" -Raw
    if ($content -notmatch "function show") { throw "Missing show()" }
    if ($content -notmatch "function store") { throw "Missing store()" }
    if ($content -notmatch "\$request->validate\(") { throw "Missing validation" }
    if ($content -notmatch "(?s)'name'.*'email'.*'phone'.*'company'.*'message'") { throw "Missing field validation (name, email, phone, company, message)" }
}

Test-Check -Name "InquiryController has store with product_id + validation" -Script {
    $content = Get-Content "$ProjectRoot\app\Http\Controllers\InquiryController.php" -Raw
    if ($content -notmatch "function store") { throw "Missing store()" }
    if ($content -notmatch "\$request->validate\(") { throw "Missing validation" }
    if ($content -notmatch "'product_id'") { throw "Missing product_id validation" }
    if ($content -notmatch "(?s)'name'.*'email'.*'message'") { throw "Missing field validation" }
}

Test-Check -Name "Routes defined for all Phase 3 endpoints" -Script {
    $content = Get-Content "$ProjectRoot\routes\web.php" -Raw
    if ($content -notmatch "'tin-tuc'|/tin-tuc") { throw "Missing /tin-tuc route" }
    if ($content -notmatch "'du-an'|/du-an") { throw "Missing /du-an route" }
    if ($content -notmatch "'lien-he'|/lien-he") { throw "Missing /lien-he route" }
    if ($content -notmatch "yeu-cau-bao-gia|/yeu-cau-bao-gia") { throw "Missing /yeu-cau-bao-gia route" }
    $slugCount = [regex]::Matches($content, "\{slug\}").Count
    if ($slugCount -lt 2) { throw "Missing slug parameter routes (found $slugCount)" }
}

# ---- 6. Mailables ----
Write-Host "[6/7] Mailables" -ForegroundColor Cyan

$mailPaths = @(
    "app\Mail\AdminNotification.php",
    "app\Mail\CustomerConfirmation.php",
    "resources\views\mail\admin-notification.blade.php",
    "resources\views\mail\customer-confirmation.blade.php"
)
foreach ($m in $mailPaths) {
    $fullPath = "$ProjectRoot\$m"
    Test-Check -Name "Mail exists: $m" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $m" }
    }
}

Test-Check -Name "AdminNotification has contact/inquiry data in view" -Script {
    $content = Get-Content "$ProjectRoot\app\Mail\AdminNotification.php" -Raw
    if ($content -notmatch 'extends Mailable') { throw "Missing Mailable inheritance" }
    if ($content -notmatch 'Inquiry|Contact') { throw "Missing Inquiry|Contact type hint" }
    $blade = Get-Content "$ProjectRoot\resources\views\mail\admin-notification.blade.php" -Raw
    if ($blade -notmatch 'submission') { throw "Missing submission data in template" }
    if ($blade -notmatch 'name') { throw "Missing name in template" }
}

Test-Check -Name "CustomerConfirmation has customer data + thank you in view" -Script {
    $content = Get-Content "$ProjectRoot\app\Mail\CustomerConfirmation.php" -Raw
    if ($content -notmatch 'extends Mailable') { throw "Missing Mailable inheritance" }
    if ($content -notmatch 'Inquiry|Contact') { throw "Missing Inquiry|Contact type hint" }
    $blade = Get-Content "$ProjectRoot\resources\views\mail\customer-confirmation.blade.php" -Raw
    if ($blade -notmatch 'submission') { throw "Missing submission data in template" }
    if ($blade -notmatch 'submission->name') { throw "Missing name rendering" }
    if ($blade -notmatch 'thank-you') { throw "Missing thank-you section" }
    if ($blade -notmatch 'c.m.on|thank') { throw "Missing thank-you message text" }
}

# ---- 7. Tests ----
Write-Host "[7/7] Tests" -ForegroundColor Cyan

$testPaths = @(
    "tests\Feature\Filament\PostResourceTest.php",
    "tests\Feature\Filament\ProjectResourceTest.php",
    "tests\Feature\Filament\InquiryResourceTest.php",
    "tests\Feature\Filament\ContactResourceTest.php",
    "tests\Feature\Http\PostFrontendTest.php",
    "tests\Feature\Http\ProjectFrontendTest.php",
    "tests\Feature\Http\ContactFormTest.php",
    "tests\Feature\Http\InquiryFormTest.php",
    "tests\Feature\Http\NavbarTest.php",
    "tests\Feature\MailTest.php",
    "tests\Feature\SeederTest.php"
)
foreach ($t in $testPaths) {
    $fullPath = "$ProjectRoot\$t"
    Test-Check -Name "Test exists: $t" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $t" }
    }
}

# ---- Run PHPUnit tests (Phase 3 filter) ----
Write-Host "`n--- Running PHPUnit tests (Phase 3) ---" -ForegroundColor Yellow
try {
    $testOutput = & $PHP artisan test --filter='PostResource|ProjectResource|InquiryResource|ContactResource|PostFrontend|ProjectFrontend|ContactForm|InquiryForm|NavbarTest|MailTest|SeederTest' 2>&1
    $exitCode = $LASTEXITCODE
    # Strip ANSI escape codes for clean parsing
    $ansiRegex = [regex]'\x1b\[[0-9;]*[mK]'
    $cleanOutput = $testOutput | Out-String
    $cleanOutput = $ansiRegex.Replace($cleanOutput, '')
    $cleanOutput | Write-Host

    if ($exitCode -ne 0) {
        throw "PHPUnit exited with code $exitCode"
    }
    Write-Host "  [PASS] All Phase 3 PHPUnit tests passed (exit code 0)" -ForegroundColor Green
    $script:passed++
} catch {
    Write-Host "  [FAIL] PHPUnit tests" -ForegroundColor Red
    Write-Host "         $_" -ForegroundColor Gray
    $script:failed++
}

# ---- Summary ----
Write-Host "=========================================" -ForegroundColor Cyan
$total = $passed + $failed
if ($failed -eq 0) {
    Write-Host "  Phase 3 VERIFIED - $passed/$total checks passed" -ForegroundColor Green
    Write-Host "=========================================`n" -ForegroundColor Cyan
    exit 0
} else {
    Write-Host "  Phase 3 - $passed/$total passed, $failed FAILED" -ForegroundColor Red
    Write-Host "  Review failures above before proceeding to Phase 4." -ForegroundColor Yellow
    Write-Host "=========================================`n" -ForegroundColor Cyan
    exit 1
}
